---
path: app/Models/InterestAndPassionQuestion.php
type: model
table: interest_and_passion_questions
fillable: [user_id, ministry_category_id, question_number, question_en, question_tl]
hidden: []
casts: []
traits: []
relationships:
  - BelongsTo: User
  - BelongsTo: MinistryCategory
scopes: []
observers: []
status: draft
last_updated: 2026-07-28
---

# InterestAndPassionQuestion

## Purpose
Interest and passion questions for Phase 3 of the assessment. Each ministry category has 5 questions. Assessees indicate their level of interest/passion for each category.

## Relationships
- `user()` — BelongsTo to `User`
- `ministryCategory()` — BelongsTo to `MinistryCategory`

## Notable behavior
- Unique constraint on `(user_id, ministry_category_id, question_number)`.
- Bilingual (English and Tagalog).
- Copied from user ID=1 during registration via `CopyDefaults`.
- Admin can customize via `QuestionController@interest`/`updateInterest`.

## References
- [Laravel Eloquent — Getting Started](https://laravel.com/docs/eloquent)
- [Laravel Eloquent — Mutators & Casts](https://laravel.com/docs/eloquent-mutators)
- [Laravel Eloquent — Scopes](https://laravel.com/docs/eloquent#local-scopes)
- [Laravel Eloquent — Relationships](https://laravel.com/docs/eloquent-relationships)
- [Laravel Eloquent — Observers](https://laravel.com/docs/eloquent#observers)
- [Laravel Eloquent — Serialization](https://laravel.com/docs/eloquent-serialization)
