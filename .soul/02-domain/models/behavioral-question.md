---
path: app/Models/BehavioralQuestion.php
type: model
table: behavioral_questions
fillable: [user_id, ministry_id, question_number, question_en, question_tl]
hidden: []
casts: []
traits: []
relationships:
  - BelongsTo: User
  - BelongsTo: Ministry
scopes: []
observers: []
status: draft
last_updated: 2026-07-28
---

# BehavioralQuestion

## Purpose
Behavioral/aptitude questions for Phase 4 of the assessment. Each eligible ministry (filtered by demographics + skills) has 5 behavioral questions.

## Relationships
- `user()` — BelongsTo to `User`
- `ministry()` — BelongsTo to `Ministry`

## Notable behavior
- Unique constraint on `(user_id, ministry_id, question_number)`.
- Bilingual (English and Tagalog).
- Copied from user ID=1 during registration via `CopyDefaults`.
- Admin can customize via `QuestionController@behavioral`/`updateBehavioral`.

## References
- [Laravel Eloquent — Getting Started](https://laravel.com/docs/eloquent)
- [Laravel Eloquent — Mutators & Casts](https://laravel.com/docs/eloquent-mutators)
- [Laravel Eloquent — Scopes](https://laravel.com/docs/eloquent#local-scopes)
- [Laravel Eloquent — Relationships](https://laravel.com/docs/eloquent-relationships)
- [Laravel Eloquent — Observers](https://laravel.com/docs/eloquent#observers)
- [Laravel Eloquent — Serialization](https://laravel.com/docs/eloquent-serialization)
