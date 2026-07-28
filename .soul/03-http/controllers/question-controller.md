---
path: app/Http/Controllers/Admin/QuestionController.php
type: controller
model: [SkillQuestion, InterestAndPassionQuestion, BehavioralQuestion]
methods: [skill, interest, behavioral, updateSkill, updateInterest, updateBehavioral, resetSkill, resetInterest, resetBehavioral]
routes:
  - name: admin.questions.skill
    method: GET
    uri: /admin/questions/skill
  - name: admin.questions.skill.update
    method: POST
    uri: /admin/questions/skill/update
  - name: admin.questions.skill.reset
    method: POST
    uri: /admin/questions/skill/reset
  - name: admin.questions.interest
    method: GET
    uri: /admin/questions/interest
  - name: admin.questions.interest.update
    method: POST
    uri: /admin/questions/interest/update
  - name: admin.questions.interest.reset
    method: POST
    uri: /admin/questions/interest/reset
  - name: admin.questions.behavioral
    method: GET
    uri: /admin/questions/behavioral
  - name: admin.questions.behavioral.update
    method: POST
    uri: /admin/questions/behavioral/update
  - name: admin.questions.behavioral.reset
    method: POST
    uri: /admin/questions/behavioral/reset
middleware: [admin]
form_requests: []
resources_used: []
status: draft
last_updated: 2026-07-28
---

# QuestionController

## Purpose
Manages custom assessment questions for the authenticated admin across all 3 question types (skills, interests & passions, behavioral). Allows viewing, updating, and resetting to defaults.

## Methods
- `skill()` — Returns skill questions management page.
- `interest()` — Returns interest & passion questions management page.
- `behavioral()` — Returns behavioral questions management page.
- `updateSkill(Request)` — Updates skill questions for the current admin.
- `updateInterest(Request)` — Updates interest & passion questions.
- `updateBehavioral(Request)` — Updates behavioral questions.
- `resetSkill()` — Resets skill questions to defaults (re-copied from user ID=1).
- `resetInterest()` — Resets interest questions to defaults.
- `resetBehavioral()` — Resets behavioral questions to defaults.

### Private helpers
- `updateQuestions()` — Shared logic for updating question records.
- `resetQuestions()` — Shared logic for resetting to default template from user ID=1.

## Authorization
Protected by `admin` middleware.

## References
- [Laravel Controllers](https://laravel.com/docs/controllers)
- [Laravel Controller Middleware](https://laravel.com/docs/controllers#controller-middleware)
- [Laravel Resource Controllers](https://laravel.com/docs/controllers#resource-controllers)
- [Laravel Dependency Injection](https://laravel.com/docs/controllers#dependency-injection-and-controllers)
