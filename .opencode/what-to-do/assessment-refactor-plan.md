# Assessment Refactor: Livewire Wizard (From-Scratch Architecture)

## Philosophy

The assessment wizard is a **server-driven multi-step form**. JavaScript is only responsible
for UI polish (puzzle, chart, animations). All logic — validation, scoring, filtering,
ranking, session persistence — lives in Laravel services and Livewire components.

The old `assessment.js` (1700 lines) is replaced by two anonymous Livewire components.
About 1600 lines of JS are deleted; ~100 lines remain for UX enhancements.

## Data Flow

```
User visits /assessment/{code}

AssessmentController@index(code)
  → AssessmentService::getAssessmentData(code)    ← questions, restrictions, ministries
  → view('assessment.index', ['data' => $data])

assessment/index.blade.php
  → <livewire:⚡demographic-wizard :data="$data" />
  → <livewire:⚡assessment-wizard :data="$data" />

⚡demographic-wizard (mount)
  → Receives data, renders step 1 form (personal details)
  → On submit: validates, stores in session/DB, emits step-completed event

⚡assessment-wizard (mount)
  → Receives data, reads session for step progress
  → Renders steps 2-5 based on current step
  → Business logic delegated to MinistryMatchingService + AssessmentService
  → On final submit: creates UserReport(status: completed)
```

## Files to Delete

| File | Reason |
|------|--------|
| `resources/views/assessment/demographicProfilling.blade.php` | Content moved to `⚡demographic-wizard` |
| `resources/views/assessment/skillProfilling.blade.php` | Content moved to `⚡assessment-wizard` |
| `resources/views/assessment/interestAndPassionProfilling.blade.php` | Content moved to `⚡assessment-wizard` |
| `resources/views/assessment/behavioralProfilling.blade.php` | Content moved to `⚡assessment-wizard` |
| `resources/views/assessment/result.blade.php` | Content moved to `⚡assessment-wizard` |
| `app/Http/Resources/Assessment/AssessmentDataResource.php` | Replaced by Livewire data prop |
| `app/Http/Resources/Assessment/UserReportResource.php` | Replaced by Livewire submission |
| `app/Http/Requests/Assessment/StoreUserReportRequest.php` | Validation handled in Livewire rules |
| `app/Http/Controllers/Assessment/ReportController.php` | Submission handled by Livewire |

## Files to Create / Expand

| File | Purpose |
|------|---------|
| `resources/views/components/⚡demographic-wizard.blade.php` | **Expand** — step 1 personal details form |
| `resources/views/components/⚡assessment-wizard.blade.php` | **Expand** — steps 2-5 wizard |
| `app/Services/MinistryMatchingService.php` | **Expand** — real filtering + ranking logic |
| | `filterByDemographic(ministries, restrictions, userData)` |
| | `filterBySkill(ministries, restrictions, skillScores)` |
| | `filterByInterest(ministries, interestScores)` |
| | `rankMinistries(ministries, behavioralScores)` → sorted by match % |

## Files to Modify

| File | Change |
|------|--------|
| `resources/views/assessment/index.blade.php` | Replace `@include(...)` calls with `<livewire:...>` tags. Keep banner, step counter, instructions. Remove `window.__ASSESSMENT_DATA__` (data lives in Livewire props now). |
| `app/Http/Controllers/Assessment/AssessmentController.php` | `index()` passes `$data` to view. `getData()` can be deleted if no external consumers. |
| `app/Models/UserReport.php` | Add `$fillable` fields for: `status` (in_progress / completed), `step` (int), `phase1_data` (json), `phase2_data` (json), `phase3_data` (json), `phase4_data` (json), `phase5_data` (json). The JS localStorage data now lives in DB columns. |
| `resources/js/assessment.js` | Delete ~1600 lines. Keep only: puzzle animation, Chart.js rendering, blinkInput, radio highlights, Intersection Observer. |
| `routes/web.php` | Keep `GET /assessment/{code?}`. Route now just points to Livewire-rendered view. |
| `routes/api.php` | Remove `POST /assessment-data` and `POST /user-reports` — no longer consumed by JS. Keep if external consumers exist. |

## Livewire Component: ⚡demographic-wizard

### Responsibility
Step 1 of 5 — collect personal details (name, email, contact, gender, age, marital,
baptized, time-in-faith). Validate all fields. Store in session/DB on completion.

### Props
```php
public array $data;  // from AssessmentService (ministries, restrictions, questions)
```

### Properties (state)
```php
public string $name = '';
public string $email = '';
public string $contactNo = '';
public int $gender = 0;
public int $age = 0;
public int $maritalStatus = 0;
public int $baptized = 0;
public int $timeInFaith = 0;
```

### Validation
```php
protected function rules(): array
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'contactNo' => 'required|string|max:20',
        'gender' => 'required|integer|in:1,2',
        'age' => 'required|integer|min:1|max:120',
        'maritalStatus' => 'required|integer|in:1,2',
        'baptized' => 'required|integer|in:1,2',
        'timeInFaith' => 'required|integer|in:1,2,3,4',
    ];
}
```

### View (blade)
- Renders the same form fields as `demographicProfilling.blade.php` but as Blade
  components with `wire:model` bindings
- Submit button calls `wire:submit="submit"` → validates → stores data → emits
  `step-completed` event with step number and data

### Methods
```php
public function mount(): void
{
    // Nothing to mount — form starts empty
}

public function submit(): void
{
    $this->validate();
    // Store phase1 data in session:
    session()->put('assessment.phase1', $this->all());
    session()->put('assessment.step', 2);
    // Emit event for assessment-wizard to pick up:
    $this->dispatch('stepCompleted', step: 2)->to('⚡assessment-wizard');
}
```

## Livewire Component: ⚡assessment-wizard

### Responsibility
Steps 2-5 of 5 — skill profiling, interest/passion profiling, behavioral profiling,
result display. Owns all question rendering, scoring computation, ministry matching,
and final report saving.

### Props
```php
public array $data;           // from AssessmentService (questions, ministries, restrictions)
public int $resumeStep = 1;   // from session, defaults to 1
```

### Properties (state)
```php
// Step tracking
public int $step = 1;

// Step 2 — Skill
public int $skillQuestionIndex = 0;
public array $skillScores = [0, 0, 0, 0, 0, 0, 0, 0];

// Step 3 — Interest
public array $interestScores = [];

// Step 4 — Behavioral
public array $behavioralScores = [];

// Step 5 — Result (computed, not stored)
public array $rankedMinistries = [];
public array $eligibleMinistries = [];
```

### Key Methods
```php
public function mount(): void
{
    // Resume from session if exists
    $sessionStep = session('assessment.step', 1);
    $this->step = max($this->resumeStep, $sessionStep);
    // Restore previous phase data from session
    $this->restoreSession();
}

// Step 2
public function setSkillScore(int $index, int $score): void  // wire:click
public function skillSubmit(): void  // Compute score, check demographic restrictions
    // → $this->eligibleMinistries = MinistryMatchingService::filterByDemographic(...)
    // → $this->eligibleMinistries = MinistryMatchingService::filterBySkill(...)
    // → session()->put('assessment.phase2', $this->skillScores)
    // → $this->step = 3

// Step 3
public function setInterestScore(int $index, int $score): void
public function interestSubmit(): void
    // → $this->eligibleMinistries = MinistryMatchingService::filterByInterest(...)
    // → session()->put('assessment.phase3', ['scores' => $this->interestScores, 'eligible' => $this->eligibleMinistries])
    // → $this->step = 4

// Step 4
public function setBehavioralScore(int $index, int $score): void
public function behavioralSubmit(): void
    // → $this->rankedMinistries = MinistryMatchingService::rankMinistries(...)
    // → session()->put('assessment.phase4', $this->behavioralScores)
    // → $this->step = 5

// Step 5
public function submitResult(): void
    // → UserReport::create([...all data..., 'status' => 'completed'])
    // → session()->forget('assessment.*')
    // → Redirect to /assessment/{code}/thank-you or show success message
```

### View (blade)
- Uses `@if($step === 2)`, `@elseif($step === 3)`, etc. to render current step
- Rather than JS cloning templates, Blade loops render questions:
  ```blade
  @foreach($data['questions_skill'] as $i => $q)
    <div class="questionBox @if($i !== $skillQuestionIndex) denyInput @endif">
      <p>{{ $q['skillQuestion_en'] }}</p>
      <div class="rateBox">
        @foreach([6,5,4,3,2,1] as $val)
          <button wire:click="setSkillScore({{ $i }}, {{ $val }})"
                  class="rateWhite @if($skillScores[$i] === $val) clicked @endif">
            {{ $val }}
          </button>
        @endforeach
      </div>
    </div>
  @endforeach
  ```
- Result step renders Chart.js (canvas element initialized by JS when Alpine/Livewire
  fires `x-init` or `wire:init`)
- Translation strings accessed via `__('key')` or passed from `$data`

## Business Logic: MinistryMatchingService

The service currently has stub methods. They must be implemented with real logic:

```php
class MinistryMatchingService
{
    /**
     * Filter ministries by demographic restrictions.
     * Each restriction has: ministry_id, field, operator, value.
     * Check userData against each restriction.
     */
    public function filterByDemographic(array $ministries, array $restrictions, array $userData): array;

    /**
     * Filter by skill scores — only include ministries where
     * user meets the skill threshold.
     */
    public function filterBySkill(array $ministries, array $restrictions, array $skillScores): array;

    /**
     * Filter by interest scores — rank/select ministries
     * matching the user's top interest areas.
     */
    public function filterByInterest(array $ministries, array $interestScores): array;

    /**
     * Final ranking — score each eligible ministry based on
     * behavioral answers and return sorted array.
     */
    public function rankMinistries(array $ministries, array $behavioralScores): array;
}
```

## Session Persistence (replaces localStorage)

The old JS stored `phase1`–`phase5` in localStorage and checked for resume on load.
This is now handled server-side via session:

```
session()->put('assessment.step', 2);
session()->put('assessment.phase1', ['name' => '...', 'email' => '...', ...]);
session()->put('assessment.phase2', [6, 5, 4, 3, 2, 1, 0, 0]);
session()->put('assessment.phase3', [...]);
session()->put('assessment.phase4', [...]);
```

AssessmentWizard::mount() checks `session('assessment.step')` and restores state,
so a page refresh returns the user to the correct step with data intact.

## UserReport Model Changes

Add columns for session persistence:

```php
// Migration:
Schema::table('user_reports', function (Blueprint $table) {
    $table->string('status')->default('in_progress');  // in_progress | completed
    $table->integer('step')->default(1);                // current wizard step
    $table->json('phase1_data')->nullable();
    $table->json('phase2_data')->nullable();
    $table->json('phase3_data')->nullable();
    $table->json('phase4_data')->nullable();
    $table->json('phase5_data')->nullable();
});
```

Alternative: use session only (no DB writes until final submit). Simpler and avoids
migration. The old JS used localStorage which is session-like anyway. For the plan,
**session-only** is preferred — no DB writes until the final submission.

## JS After Refactor (~100 lines)

Remaining responsibilities:

| Feature | Lines | Location |
|---------|-------|----------|
| Puzzle corner animation | ~20 | On step 5, animate puzzle pieces into corners of chart |
| Chart.js rendering | ~30 | In step 5, render radar/bar chart from `rankedMinistries` |
| blinkInput animation | ~15 | Flash invalid input fields red on validation error |
| Radio button highlights | ~15 | Toggle `.clicked` class on option buttons |
| Intersection Observer | ~20 | Animate elements into view on scroll (move-up class) |

Everything else from `assessment.js` is deleted:
- `verifyPhase1-4`, `showQuestions`, `gotoPhase2-5`, `interpret`, `reviewAns`
- `skillSubmit`, `interestSubmit`, `behavioralSubmit`
- `errorFound`, `showAlert`, `showMessage` (replaced by Livewire validation)
- `saveUserReport` (replaced by Livewire submit + UserReport::create)
- `uiTranslation` object (replaced by Laravel's `__()` or `@lang`)
- `goToStep` (replaced by Livewire's `$this->step = N`)
- `exportResult` (can remain if needed, or become a Livewire download method)

## What assessment/index.blade.php Looks Like After

```blade
@extends('_layouts.assessment')

@section('title', 'PERFIT — Assessment')

@section('content')
<div class="banner">
    <img src="{{ asset('images/banner.png') }}">
    <p class="ttl move-up" translate="title">ASSESSMENT</p>
</div>

{{-- Step counter (same as current) --}}
<div class="stepCounter move-up">
    <div class="stepContainer">
        <p class="step currentStep" id="s1">1</p>
        <p translate="t1">Personal Details</p>
    </div>
    <p class="line" id="l1"></p>
    <div class="stepContainer">
        <p class="step" id="s2">2</p>
        <p translate="t2">Skill Profiling</p>
    </div>
    <p class="line" id="l2"></p>
    <div class="stepContainer">
        <p class="step" id="s3">3</p>
        <p translate="t3">Interest &amp; Passion Profiling</p>
    </div>
    <p class="line" id="l3"></p>
    <div class="stepContainer">
        <p class="step" id="s4">4</p>
        <p translate="t4">Behavioral Profiling</p>
    </div>
    <p class="line" id="l4"></p>
    <div class="stepContainer">
        <p class="step" id="s5">5</p>
        <p translate="t5">Result</p>
    </div>
</div>

<p class="instruction" id="inst1" translate="i1"
   style="text-align: justify;">
    INSTRUCTION: Please provide your personal details accurately.
</p>

<div class="instruction" style="display: none;" id="inst2">
    <p translate="i2" style="text-align: justify;">
        INSTRUCTION: Please read each statement carefully and answer honestly.
    </p>
    <hr>
    <p translate="ii1">Strongly Agree = 6 Points</p>
    <p translate="ii2">Agree = 5 Points</p>
    <p translate="ii3">Slightly Agree = 4 Points</p>
    <p translate="ii4">Slightly Disagree = 3 Points</p>
    <p translate="ii5">Disagree = 2 Points</p>
    <p translate="ii6">Strongly Disagree = 1 Point</p>
</div>

{{-- Livewire components --}}
<livewire:⚡demographic-wizard :data="$data" wire:key="demo-wizard" />
<livewire:⚡assessment-wizard :data="$data" wire:key="asses-wizard" />
@endsection
```

## Routes After Cleanup

```php
// routes/web.php
Route::get('/assessment/{code?}', [AssessmentController::class, 'index'])
    ->name('assessment');

// routes/api.php — both routes removed (no JS fetch consumers)
// DELETE: POST /api/assessment-data
// DELETE: POST /api/user-reports
```

If external consumers need the API, keep `getData()` and the route in api.php.
For a from-scratch app, neither API endpoint exists.

## Controllers After Cleanup

```php
class AssessmentController extends Controller
{
    public function __construct(private AssessmentService $assessmentService) {}

    public function index(?string $code = null)
    {
        $churchCode = $code ?? request()->query('code');

        if (empty($churchCode)) {
            return view('assessment.index');
        }

        $data = $this->assessmentService->getAssessmentData($churchCode);

        return view('assessment.index', compact('data'));
    }
}
```

`ReportController`, `AssessmentDataResource`, `UserReportResource`, and
`StoreUserReportRequest` are all deleted — Livewire handles everything.

## Step-by-Step Execution

1. **Expand `MinistryMatchingService`** — implement `filterByDemographic()`,
   `filterByInterest()`, `filterBySkill()`, `rankMinistries()` with real logic
   extracted from the old JS functions.
2. **Remove `window.__ASSESSMENT_DATA__`** from `assessment/index.blade.php` —
   no longer needed; data flows through Livewire props.
3. **Replace `@include(...)` with `<livewire:...>`** in
   `assessment/index.blade.php` — remove all 5 includes, add the two
   Livewire component tags.
4. **Expand `⚡demographic-wizard`** — add properties, validation, mount(), submit()
   for step 1 personal details form.
5. **Expand `⚡assessment-wizard`** — add all logic for steps 2-5 (skill, interest,
   behavioral, result), including question rendering with Blade loops.
6. **Trim `assessment.js`** — delete all functions that Livewire now owns. Keep only
   puzzle, chart, blinkInput, highlights, observer.
7. **Delete dead files** — remove the 5 individual blade views, ReportController,
   StoreUserReportRequest, AssessmentDataResource, UserReportResource.
8. **Update routes** — remove `POST /api/assessment-data` and `POST /api/user-reports`
   from api.php (if no external consumers).
9. **Run migration** if adding `status`/`step` columns to `user_reports` (optional —
   session-only is simpler).
10. **Build & verify** — `npm run build`, `php artisan route:list`, test full
    assessment flow.

## What Stays Untouched

- AssessmentService (data fetching unchanged)
- UserReport model (minor additions only)
- `_layouts/assessment.blade.php` (layout)
- `_partials/assessmentSide/footer.blade.php` (footer)
- CSS stylesheets
- Puzzle images in `public/images/`
- Vite config
