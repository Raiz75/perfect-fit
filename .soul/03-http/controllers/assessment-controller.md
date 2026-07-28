---
path: app/Http/Controllers/Assessment/AssessmentController.php
type: controller
model: [UserReport, DemographicRestriction, SkillRestriction, SkillQuestion, InterestAndPassionQuestion, BehavioralQuestion, Ministry]
methods: [setChurchCode, show, storePhase1, reset, done, storePhase2, storePhase3, storePhase4]
routes:
  - name: assessment.set-church-code
    method: POST
    uri: /assessment/set-church-code
  - name: assessment.index
    method: GET
    uri: /assessment
  - name: assessment.phase1.store
    method: POST
    uri: /assessment/phase1
  - name: assessment.reset
    method: GET
    uri: /assessment/reset
  - name: assessment.done
    method: POST
    uri: /assessment/done
  - name: assessment.phase2.store
    method: POST
    uri: /assessment/phase2
  - name: assessment.phase3.store
    method: POST
    uri: /assessment/phase3
  - name: assessment.phase4.store
    method: POST
    uri: /assessment/phase4
middleware: []
form_requests: [StoreDemographicsRequest]
resources_used: []
status: draft
last_updated: 2026-07-28
---

# AssessmentController

## Purpose
Handles the 4-phase ministry fit assessment flow. All state is session-based — no authentication required. The assessment is entirely standard HTML form POSTs (no AJAX, no Livewire).

## Methods
- `setChurchCode(Request)` — Validates and stores the church code in session.
- `show()` — Displays the current assessment phase based on session state.
- `storePhase1(StoreDemographicsRequest)` — Stores demographic data (name, email, contact, gender, age, marital status, baptized, time in faith).
- `storePhase2(Request)` — Stores skill self-ratings (8 skills × 5 questions, 1-6 scale).
- `storePhase3(Request)` — Stores interest & passion ratings (6 categories × 5 questions).
- `storePhase4(Request)` — Stores behavioral responses (5 per eligible ministry).
- `reset()` — Clears all assessment session data.
- `done()` — Computes eligible ministries, calls DeepSeek AI for interpretation, saves `UserReport`, and shows results.

### Private helpers
- `computeEligibleMinistries()` — Filters ministries based on demographic and skill restrictions.
- `rankMinistries()` — Ranks eligible ministries by assessment scores.
- `computeTiers()` — Groups ranked ministries into tiers.

## Authorization
No authorization — the assessment is publicly accessible with only a church code.

## References
- [Laravel Controllers](https://laravel.com/docs/controllers)
- [Laravel Controller Middleware](https://laravel.com/docs/controllers#controller-middleware)
- [Laravel Resource Controllers](https://laravel.com/docs/controllers#resource-controllers)
- [Laravel Dependency Injection](https://laravel.com/docs/controllers#dependency-injection-and-controllers)
