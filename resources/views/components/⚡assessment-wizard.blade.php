<?php

use Livewire\Component;
use App\Services\MinistryMatchingService;
use App\Models\UserReport;

new class extends Component
{
    public array $data;
    public int $step = 1;
    public string $lang = 'en';

    public array $skillAnswers = [];
    public array $skillScores = [0, 0, 0, 0, 0, 0, 0, 0];
    public int $skillCurrentQ = 0;
    public bool $skillComplete = false;

    public array $interestAnswers = [];
    public array $interestScores = [0, 0, 0, 0, 0, 0];
    public int $interestCurrentQ = 0;
    public bool $interestComplete = false;

    public array $behavioralAnswers = [];
    public array $behavioralScores = [];
    public int $behavioralCurrentQ = 0;
    public bool $behavioralComplete = false;

    public array $eligibleMinistries = [];
    public array $selectedMinistryNames = [];
    public array $activeBehavioralQuestions = [];

    public bool $reportSaved = false;

    public function mount(): void
    {
        $sessionStep = session('assessment.step', 1);
        $this->step = $sessionStep;
        $this->lang = session('assessment.lang', 'en');

        if ($sessionStep > 1) {
            $this->restoreSession();
        }
    }

    private function restoreSession(): void
    {
        $phase1 = session('assessment.phase1', []);
        if (!empty($phase1)) {
            $this->skillComplete = true;
        }

        $phase2 = session('assessment.phase2', []);
        if (!empty($phase2)) {
            $this->skillScores = $phase2;
            $this->skillComplete = true;
            foreach ($this->skillScores as $i => $score) {
                for ($j = 0; $j < 5; $j++) {
                    $this->skillAnswers[$i * 5 + $j] = 1;
                }
            }
            $this->skillCurrentQ = 40;
        }

        $phase3 = session('assessment.phase3', []);
        if (!empty($phase3)) {
            $this->selectedMinistryNames = $phase3['names'] ?? [];
            $this->eligibleMinistries = $phase3['eligible'] ?? [];
            $this->computeBehavioralQuestions();
        }
    }

    #[On('setLang')]
    public function setLanguage($lang): void
    {
        $this->lang = $lang;
        session()->put('assessment.lang', $lang);
    }

    #[On('stepCompleted')] 
    public function advanceStep($step): void
    {
        if ($step === 2) {
            $phase1 = session('assessment.phase1');
            if (!$phase1) return;

            $service = app(MinistryMatchingService::class);
            $eligibility = $service->checkGlobalEligibility(
                $this->data['restrictions_demographic'] ?? [],
                $phase1
            );

            if (!$eligibility['eligible']) {
                $this->dispatch('eligibilityError', 
                    reason: $eligibility['reason'] ?? 'unknown',
                    min: $eligibility['min'] ?? 0,
                    max: $eligibility['max'] ?? 100
                );
                return;
            }
        }

        $this->step = $step;
        session()->put('assessment.step', $step);
        if ($step === 2) {
            $this->skillCurrentQ = 0;
        }
    }

    private function recalculateGroupScore(array &$scores, array $answers, int $groupIdx, int $groupSize): void
    {
        $sum = 0;
        $start = $groupIdx * $groupSize;
        for ($i = $start; $i < $start + $groupSize; $i++) {
            if (isset($answers[$i])) {
                $sum += (int) $answers[$i];
            }
        }
        $scores[$groupIdx] = $sum;
    }

    public function rateSkill(int $qIdx, int $value): void
    {
        $this->skillAnswers[$qIdx] = $value;
        $this->recalculateGroupScore($this->skillScores, $this->skillAnswers, intdiv($qIdx, 5), 5);
        if ($qIdx >= $this->skillCurrentQ) {
            $this->skillCurrentQ = $qIdx + 1;
        }
        if (count($this->skillAnswers) >= 40) {
            $this->skillComplete = true;
        }
    }

    public function submitPhase2(): void
    {
        session()->put('assessment.phase2', $this->skillScores);
        session()->put('assessment.step', 3);

        $this->step = 3;
        $this->loadPhase3Questions();
    }

    public function loadPhase3Questions(): void
    {
        $this->interestCurrentQ = 0;
        $this->interestComplete = false;
        $this->interestAnswers = [];
        $this->interestScores = [0, 0, 0, 0, 0, 0];
    }

    public function rateInterest(int $qIdx, int $value): void
    {
        $this->interestAnswers[$qIdx] = $value;
        $this->recalculateGroupScore($this->interestScores, $this->interestAnswers, intdiv($qIdx, 5), 5);
        if ($qIdx >= $this->interestCurrentQ) {
            $this->interestCurrentQ = $qIdx + 1;
        }
        if (count($this->interestAnswers) >= 30) {
            $this->interestComplete = true;
        }
    }

    public function submitPhase3(): void
    {
        $phase1 = session('assessment.phase1', []);
        $service = app(MinistryMatchingService::class);

        $interestFiltered = $service->filterByInterest(
            $this->data['ministries'] ?? [],
            $this->interestScores
        );

        $demographicFiltered = $service->filterByDemographic(
            $interestFiltered,
            $this->data['restrictions_demographic'] ?? [],
            $phase1
        );

        $finalFiltered = $service->filterBySkill(
            $demographicFiltered,
            $this->data['restrictions_skill'] ?? [],
            $this->skillScores
        );

        $this->eligibleMinistries = $finalFiltered;
        $this->selectedMinistryNames = array_map(fn($m) => $m['name'], $finalFiltered);

        if (empty($this->eligibleMinistries)) {
            $this->dispatch('noEligibleMinistries');
            return;
        }

        session()->put('assessment.phase3', [
            'names' => $this->selectedMinistryNames,
            'eligible' => $this->eligibleMinistries,
        ]);
        session()->put('assessment.step', 4);

        $this->computeBehavioralQuestions();
        $this->step = 4;
    }

    private function computeBehavioralQuestions(): void
    {
        $service = app(MinistryMatchingService::class);
        $this->activeBehavioralQuestions = $service->getBehavioralQuestions(
            $this->eligibleMinistries,
            $this->data['questions_behavioral'] ?? []
        );
    }

    public function rateBehavioral(int $qIdx, int $value): void
    {
        $totalQ = count($this->activeBehavioralQuestions);
        $this->behavioralAnswers[$qIdx] = $value;
        $this->behavioralScores[$qIdx] = $value;
        if ($qIdx >= $this->behavioralCurrentQ) {
            $this->behavioralCurrentQ = $qIdx + 1;
        }
        if (count($this->behavioralAnswers) >= $totalQ) {
            $this->behavioralComplete = true;
        }
    }

    public function submitPhase4(): void
    {
        session()->put('assessment.phase4', $this->behavioralScores);
        session()->put('assessment.step', 5);

        $this->dispatch('phase5Ready', [
            'ministries' => $this->selectedMinistryNames,
            'scores' => $this->behavioralScores,
        ]);
        $this->step = 5;
    }

    public function submitResult(): void
    {
        $phase1 = session('assessment.phase1', []);

        $skillIndices = [];
        foreach ($this->skillScores as $idx => $score) {
            if ((int) $score >= 10) {
                $skillIndices[] = $idx + 1;
            }
        }

        $churchCode = session('assessment.church_code', '');

        UserReport::create([
            'church_code' => $churchCode,
            'name' => $phase1['name'] ?? '',
            'email' => $phase1['email'] ?? '',
            'contact_no' => '+63' . ($phase1['contact'] ?? ''),
            'gender' => $phase1['gender'] ?? 0,
            'age' => $phase1['age'] ?? 0,
            'marital_status' => $phase1['marital_status'] ?? 0,
            'baptized' => $phase1['baptized'] ?? 0,
            'time_in_faith' => $phase1['time_in_faith'] ?? 0,
            'music' => in_array(1, $skillIndices),
            'technology' => in_array(2, $skillIndices),
            'writing' => in_array(3, $skillIndices),
            'technical' => in_array(4, $skillIndices),
            'speaking' => in_array(5, $skillIndices),
            'accounting' => in_array(6, $skillIndices),
            'mentoring' => in_array(7, $skillIndices),
            'bible_knowledge' => in_array(8, $skillIndices),
            'eligible_ministry' => implode(', ', $this->selectedMinistryNames),
            'time_of_submission' => now(),
        ]);

        session()->forget('assessment');
        $this->reportSaved = true;
        $this->dispatch('reportSaved');
    }

    public function render()
    {
        $skillQuestions = $this->data['questions_skill'] ?? [];
        $interestQuestions = $this->data['questions_interest_and_passion'] ?? [];

        return <<<'HTML'
        <div wire:key="assess-wizard-body">
            {{-- STEP 2: Skill Profiling --}}
            @if($this->step === 2)
            <div id="skillQuestion">
                <div class="progressBarDiv">
                    <p translate="ph2">Skill Profiling Progress</p>
                    <div class="progressBar">
                        <div class="progressIndicator" style="width: {{ min(100, count($this->skillAnswers) / 40 * 100) }}%;"></div>
                    </div>
                </div>
                @php $sq = $this->data['questions_skill'] ?? []; @endphp
                @foreach($sq as $qIdx => $q)
                <div class="questionBox @if($qIdx > $this->skillCurrentQ) denyInput @endif"
                     wire:key="skill-q-{{ $qIdx }}">
                    <p class="questionText">{{ $q['question_' . $this->lang] ?? $q['question_en'] }}</p>
                    <div class="rateBox">
                        @foreach([6,5,4,3,2,1] as $val)
                        <button wire:click="rateSkill({{ $qIdx }}, {{ $val }})"
                                class="rateWhite @if(isset($this->skillAnswers[$qIdx]) && $this->skillAnswers[$qIdx] === $val) clicked @endif"
                                value="{{ $val }}">
                            @if($lang === 'tl')
                                {{ ['Lubos na Sumasang-ayon (6)', 'Sumasang-ayon (5)', 'Bahagyang Sumasang-ayon (4)', 'Bahagyang Hindi Sumasang-ayon (3)', 'Hindi Sumasang-ayon (2)', 'Lubos na Hindi Sumasang-ayon (1)'][6 - $val] }}
                            @else
                                {{ ['Strongly Agree (6)', 'Agree (5)', 'Slightly Agree (4)', 'Slightly Disagree (3)', 'Disagree (2)', 'Strongly Disagree (1)'][6 - $val] }}
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>
                @endforeach
                @if($this->skillComplete)
                <div class="ansDiv">
                    <button wire:click="submitPhase2" class="nextBtn" translate="b1">NEXT PHASE</button>
                </div>
                @endif
            </div>
            @endif

            {{-- STEP 3: Interest & Passion --}}
            @if($this->step === 3)
            <div id="ministryCategory">
                <div class="progressBarDiv">
                    <p translate="ph3">Interest and Passion Profiling Progress</p>
                    <div class="progressBar">
                        <div class="progressIndicator" style="width: {{ min(100, count($this->interestAnswers) / 30 * 100) }}%;"></div>
                    </div>
                </div>
                @php $iq = $this->data['questions_interest_and_passion'] ?? []; @endphp
                @foreach($iq as $qIdx => $q)
                <div class="questionBox @if($qIdx > $this->interestCurrentQ) denyInput @endif"
                     wire:key="interest-q-{{ $qIdx }}">
                    <p class="questionText">{{ $q['question_' . $this->lang] ?? $q['question_en'] }}</p>
                    <div class="rateBox">
                        @foreach([6,5,4,3,2,1] as $val)
                        <button wire:click="rateInterest({{ $qIdx }}, {{ $val }})"
                                class="rateWhite @if(isset($this->interestAnswers[$qIdx]) && $this->interestAnswers[$qIdx] === $val) clicked @endif"
                                value="{{ $val }}">
                            @if($lang === 'tl')
                                {{ ['Lubos na Sumasang-ayon (6)', 'Sumasang-ayon (5)', 'Bahagyang Sumasang-ayon (4)', 'Bahagyang Hindi Sumasang-ayon (3)', 'Hindi Sumasang-ayon (2)', 'Lubos na Hindi Sumasang-ayon (1)'][6 - $val] }}
                            @else
                                {{ ['Strongly Agree (6)', 'Agree (5)', 'Slightly Agree (4)', 'Slightly Disagree (3)', 'Disagree (2)', 'Strongly Disagree (1)'][6 - $val] }}
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>
                @endforeach
                @if($this->interestComplete)
                <div class="ansDiv">
                    <button wire:click="submitPhase3" class="nextBtn" translate="b1">NEXT PHASE</button>
                </div>
                @endif
            </div>
            @endif

            {{-- STEP 4: Behavioral --}}
            @if($this->step === 4)
            <div id="ministrySpecific">
                <div class="progressBarDiv">
                    <p translate="ph4">Behavioral Profiling Progress</p>
                    <div class="progressBar">
                        <div class="progressIndicator" style="width: {{ $this->activeBehavioralQuestions ? min(100, count($this->behavioralAnswers) / count($this->activeBehavioralQuestions) * 100) : 0 }}%;"></div>
                    </div>
                </div>
                @php $bq = $this->activeBehavioralQuestions; @endphp
                @foreach($bq as $qIdx => $q)
                <div class="questionBox @if($qIdx > $this->behavioralCurrentQ) denyInput @endif"
                     wire:key="behav-q-{{ $qIdx }}">
                    <p class="questionText">{{ $q['question_' . $this->lang] ?? $q['question_en'] }}</p>
                    <div class="rateBox">
                        @foreach([6,5,4,3,2,1] as $val)
                        <button wire:click="rateBehavioral({{ $qIdx }}, {{ $val }})"
                                class="rateWhite @if(isset($this->behavioralAnswers[$qIdx]) && $this->behavioralAnswers[$qIdx] === $val) clicked @endif"
                                value="{{ $val }}">
                            @if($lang === 'tl')
                                {{ ['Lubos na Sumasang-ayon (6)', 'Sumasang-ayon (5)', 'Bahagyang Sumasang-ayon (4)', 'Bahagyang Hindi Sumasang-ayon (3)', 'Hindi Sumasang-ayon (2)', 'Lubos na Hindi Sumasang-ayon (1)'][6 - $val] }}
                            @else
                                {{ ['Strongly Agree (6)', 'Agree (5)', 'Slightly Agree (4)', 'Slightly Disagree (3)', 'Disagree (2)', 'Strongly Disagree (1)'][6 - $val] }}
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>
                @endforeach
                @if($this->behavioralComplete)
                <div class="ansDiv">
                    <button wire:click="submitPhase4" class="nextBtn" translate="b1">NEXT PHASE</button>
                </div>
                @endif
            </div>
            @endif

            {{-- STEP 5: Result --}}
            @if($this->step === 5)
            <div class="responseDiv" id="responseDiv">
                <div class="part1" id="part1">
                    <h1 style="color:rgb(128,65,128);text-align:center;" translate="r1">CALLING AND SERVICE PROFILE</h1>
                    <div id="thinking" style="display:block;font-weight:bold;font-size:1.2em;font-family:sans-serif;">
                        Analyzing your gifts and calling<span id="dots">.</span>
                    </div>
                    <div id="resultPlaceholder" style="text-align:center;padding:2rem;color:rgb(128,65,128);">
                        <p style="font-size:1.5rem;" translate="r2">Your results will be available soon.</p>
                    </div>
                </div>
                <canvas class="topMinistryChart" id="topMinistryChart" style="display:none;"></canvas>
                <div class="part2" id="part2"></div>
                <div class="ansDiv" style="display:flex;gap:1rem;justify-content:center;">
                    @if(!$this->reportSaved)
                    <button wire:click="submitResult" class="nextBtn" id="step5" translate="b3">SUBMIT</button>
                    @else
                    <button id="exportBtn" class="nextBtn" translate="b44">EXPORT</button>
                    <button onclick="localStorage.clear();window.location.href='/';" class="nextBtn" translate="b4">DONE</button>
                    @endif
                </div>
            </div>
            @endif
        </div>
        HTML;
    }
};
?>

<div>
</div>
