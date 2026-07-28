---
path: app/Models/SkillQuestion.php
type: model
table: skill_questions
fillable: [user_id, skill_id, question_number, question_en, question_tl]
hidden: []
casts: []
traits: []
relationships:
  - BelongsTo: User
  - BelongsTo: Skill
scopes: []
observers: []
status: draft
last_updated: 2026-07-28
---

# SkillQuestion

## Purpose
Self-assessment questions for Phase 2 of the assessment. Each skill has 5 questions, each available in English (`question_en`) and Tagalog (`question_tl`). Assessees rate themselves on a 1-6 scale per question.

## Relationships
- `user()` — BelongsTo to `User`
- `skill()` — BelongsTo to `Skill`

## Notable behavior
- Unique constraint on `(user_id, skill_id, question_number)`.
- Bilingual: each question has both English and Tagalog text.
- Copied from user ID=1 during new admin registration via `CopyDefaults`.
- Admin can customize questions per skill via `QuestionController`.

## References
- [Laravel Eloquent — Getting Started](https://laravel.com/docs/eloquent)
- [Laravel Eloquent — Mutators & Casts](https://laravel.com/docs/eloquent-mutators)
- [Laravel Eloquent — Scopes](https://laravel.com/docs/eloquent#local-scopes)
- [Laravel Eloquent — Relationships](https://laravel.com/docs/eloquent-relationships)
- [Laravel Eloquent — Observers](https://laravel.com/docs/eloquent#observers)
- [Laravel Eloquent — Serialization](https://laravel.com/docs/eloquent-serialization)
