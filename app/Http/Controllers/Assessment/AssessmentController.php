<?php

namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assessment\StoreDemographicsRequest;
use App\Models\BehavioralQuestion;
use App\Models\DemographicRestriction;
use App\Models\InterestAndPassionQuestion;
use App\Models\Ministry;
use App\Models\MinistryCategory;
use App\Models\Skill;
use App\Models\SkillQuestion;
use App\Models\SkillRestriction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function setChurchCode(Request $request): JsonResponse
    {
        $request->validate(['church_code' => 'required|string|max:9']);

        $exists = User::where('church_code', $request->church_code)->exists();

        if (!$exists) {
            return response()->json(['exists' => false, 'message' => 'Invalid church code.'], 404);
        }

        session(['assessment.church_code' => $request->church_code]);

        return response()->json(['exists' => true]);
    }

    public function show()
    {
        $churchCode = session('assessment.church_code', '');
        $phase1 = session('assessment.phase1', []);
        $currentPhase = session('assessment.current_phase', 1);

        $skillQuestions = collect();
        $skills = collect();
        $interestQuestions = collect();
        $ministryCategories = collect();
        $behavioralQuestions = collect();
        $eligibleMinistries = collect();

        if ($churchCode) {
            $admin = User::where('church_code', $churchCode)->first();
            if ($admin) {
                if ($currentPhase >= 2) {
                    $skillQuestions = SkillQuestion::where('user_id', $admin->id)
                        ->orderBy('skill_id')
                        ->orderBy('question_number')
                        ->get();
                    $skills = Skill::all()->keyBy('id');
                }
                if ($currentPhase >= 3) {
                    $interestQuestions = InterestAndPassionQuestion::where('user_id', $admin->id)
                        ->orderBy('ministry_category_id')
                        ->orderBy('question_number')
                        ->get();
                    $ministryCategories = MinistryCategory::all()->keyBy('id');
                }
                if ($currentPhase >= 4) {
                    $phase2 = session('assessment.phase2', []);
                    $phase3 = session('assessment.phase3', []);
                    $eligibleMinistries = $this->computeEligibleMinistries($admin, $phase1, $phase2, $phase3);
                    $behavioralQuestions = BehavioralQuestion::where('user_id', $admin->id)
                        ->whereIn('ministry_id', $eligibleMinistries->pluck('id'))
                        ->orderBy('ministry_id')
                        ->orderBy('question_number')
                        ->get();
                }
            }
        }

        return view('assessment.index', compact('phase1', 'currentPhase', 'skillQuestions', 'skills', 'interestQuestions', 'ministryCategories', 'behavioralQuestions', 'eligibleMinistries'));
    }

    private function computeEligibleMinistries($admin, $phase1, $phase2, $phase3): \Illuminate\Support\Collection
    {
        $ministries = Ministry::all();
        $phase3GroupTotals = $phase3['groupTotals'] ?? [];

        if (empty($phase3GroupTotals)) {
            return collect();
        }

        $maxScore = max($phase3GroupTotals);
        $topCategories = array_keys(array_filter($phase3GroupTotals, function ($score) use ($maxScore) {
            return $score === $maxScore;
        }));

        $eligible = $ministries->filter(function ($m) use ($topCategories) {
            return in_array($m->ministry_category_id, $topCategories);
        });

        $demographicRestrictions = DemographicRestriction::where('user_id', $admin->id)
            ->get()->keyBy('ministry_id');
        $userGender = (int) ($phase1['gender'] ?? 0);
        $userAge = (int) ($phase1['age'] ?? 0);
        $userMarital = (int) ($phase1['status'] ?? 0);
        $userBaptized = (int) ($phase1['baptized'] ?? 0);
        $userFaith = (int) ($phase1['timeInFaith'] ?? 0);

        $eligible = $eligible->filter(function ($m) use ($demographicRestrictions, $userGender, $userAge, $userMarital, $userBaptized, $userFaith) {
            $r = $demographicRestrictions->get($m->id);
            if (!$r) return true;
            if ($r->gender != 0 && $r->gender != $userGender) return false;
            if ($userAge < $r->age_min || $userAge > $r->age_max) return false;
            if ($r->marital_status != 0 && $r->marital_status != $userMarital) return false;
            if ($r->baptized == 1 && $userBaptized != 1) return false;
            if ($userFaith < $r->time_in_faith) return false;
            return true;
        });

        $skillRestrictions = SkillRestriction::where('user_id', $admin->id)
            ->get()->keyBy('ministry_id');
        $userSkillTotals = $phase2['groupTotals'] ?? [];
        $userSkills = [];
        foreach ($userSkillTotals as $skillId => $total) {
            if ($total >= 10) {
                $userSkills[] = (int) $skillId;
            }
        }

        $eligible = $eligible->filter(function ($m) use ($skillRestrictions, $userSkills) {
            $r = $skillRestrictions->get($m->id);
            if (!$r) return true;
            $required = [];
            if ($r->music == 1) $required[] = 1;
            if ($r->technology == 1) $required[] = 2;
            if ($r->writing == 1) $required[] = 3;
            if ($r->technical == 1) $required[] = 4;
            if ($r->speaking == 1) $required[] = 5;
            if ($r->accounting == 1) $required[] = 6;
            if ($r->mentoring == 1) $required[] = 7;
            if ($r->bible_knowledge == 1) $required[] = 8;
            if (empty($required)) return true;
            return !empty(array_intersect($required, $userSkills));
        });

        return $eligible;
    }

    public function storePhase1(StoreDemographicsRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['church_code'] = session('assessment.church_code', '');

        session(['assessment.phase1' => $validated]);
        session(['assessment.current_phase' => 2]);

        return redirect()->route('assessment.index');
    }

    public function reset(): RedirectResponse
    {
        session()->forget('assessment');

        return redirect()->route('home');
    }

    public function storePhase2(Request $request): RedirectResponse
    {
        $answers = $request->input('answers', []);

        if (empty($answers)) {
            return redirect()->route('assessment.index')->withErrors(['answers' => 'Please answer all questions.']);
        }

        $churchCode = session('assessment.church_code', '');
        $admin = User::where('church_code', $churchCode)->first();
        $totalQuestions = $admin ? SkillQuestion::where('user_id', $admin->id)->count() : 0;

        if (count($answers) !== $totalQuestions) {
            return redirect()->route('assessment.index')->withErrors(['answers' => 'Please answer all questions before proceeding.']);
        }

        $validated = [];
        foreach ($answers as $questionId => $score) {
            $score = (int) $score;
            if ($score < 1 || $score > 6) {
                return redirect()->route('assessment.index')->withErrors(["answers.$questionId" => 'Invalid score.']);
            }
            $validated[$questionId] = $score;
        }

        $groupTotals = [];
        foreach ($validated as $questionId => $score) {
            $question = SkillQuestion::find($questionId);
            if ($question) {
                $skillId = $question->skill_id;
                if (!isset($groupTotals[$skillId])) {
                    $groupTotals[$skillId] = 0;
                }
                $groupTotals[$skillId] += $score;
            }
        }

        session(['assessment.phase2' => [
            'scores' => $validated,
            'groupTotals' => $groupTotals,
        ]]);
        session(['assessment.current_phase' => 3]);

        return redirect()->route('assessment.index');
    }

    public function storePhase3(Request $request): RedirectResponse
    {
        $answers = $request->input('answers', []);

        if (empty($answers)) {
            return redirect()->route('assessment.index')->withErrors(['answers' => 'Please answer all questions.']);
        }

        $churchCode = session('assessment.church_code', '');
        $admin = User::where('church_code', $churchCode)->first();
        $totalQuestions = $admin ? InterestAndPassionQuestion::where('user_id', $admin->id)->count() : 0;

        if (count($answers) !== $totalQuestions) {
            return redirect()->route('assessment.index')->withErrors(['answers' => 'Please answer all questions before proceeding.']);
        }

        $validated = [];
        foreach ($answers as $questionId => $score) {
            $score = (int) $score;
            if ($score < 1 || $score > 6) {
                return redirect()->route('assessment.index')->withErrors(["answers.$questionId" => 'Invalid score.']);
            }
            $validated[$questionId] = $score;
        }

        $groupTotals = [];
        foreach ($validated as $questionId => $score) {
            $question = InterestAndPassionQuestion::find($questionId);
            if ($question) {
                $catId = $question->ministry_category_id;
                if (!isset($groupTotals[$catId])) {
                    $groupTotals[$catId] = 0;
                }
                $groupTotals[$catId] += $score;
            }
        }

        session(['assessment.phase3' => [
            'scores' => $validated,
            'groupTotals' => $groupTotals,
        ]]);
        session(['assessment.current_phase' => 4]);

        return redirect()->route('assessment.index');
    }

    public function storePhase4(Request $request): RedirectResponse
    {
        $churchCode = session('assessment.church_code', '');
        $admin = User::where('church_code', $churchCode)->first();
        $phase2 = session('assessment.phase2', []);
        $phase3 = session('assessment.phase3', []);
        $phase1 = session('assessment.phase1', []);
        $eligibleMinistries = $this->computeEligibleMinistries($admin, $phase1, $phase2, $phase3);

        if ($eligibleMinistries->isEmpty()) {
            session(['assessment.phase4' => ['scores' => []]]);
            session(['assessment.current_phase' => 5]);
            session(['assessment.eligible_ministries' => '']);
            return redirect()->route('assessment.index');
        }

        $answers = $request->input('answers', []);

        if (empty($answers)) {
            return redirect()->route('assessment.index')->withErrors(['answers' => 'Please answer all questions.']);
        }

        $totalQuestions = $admin ? BehavioralQuestion::whereIn('ministry_id', $eligibleMinistries->pluck('id'))->count() : 0;

        if (count($answers) !== $totalQuestions) {
            return redirect()->route('assessment.index')->withErrors(['answers' => 'Please answer all questions before proceeding.']);
        }

        $validated = [];
        foreach ($answers as $questionId => $score) {
            $score = (int) $score;
            if ($score < 1 || $score > 6) {
                return redirect()->route('assessment.index')->withErrors(["answers.$questionId" => 'Invalid score.']);
            }
            $validated[$questionId] = $score;
        }

        session(['assessment.phase4' => ['scores' => $validated]]);
        session(['assessment.current_phase' => 5]);
        session(['assessment.eligible_ministries' => $eligibleMinistries->pluck('name')->implode(', ')]);

        return redirect()->route('assessment.index');
    }
}
