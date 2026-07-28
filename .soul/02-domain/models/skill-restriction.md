---
path: app/Models/SkillRestriction.php
type: model
table: skill_restrictions
fillable: [user_id, ministry_id, music, technology, writing, technical, speaking, accounting, mentoring, bible_knowledge]
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

# SkillRestriction

## Purpose
Defines which skills are required or relevant for a ministry. Each of the 8 skill areas is a boolean column per user-ministry pair. If a skill is marked `true`, the assessee's self-rating in that skill affects their eligibility for the ministry.

## Relationships
- `user()` — BelongsTo to `User`
- `ministry()` — BelongsTo to `Ministry`

## Notable behavior
- Unique constraint on `(user_id, ministry_id)` — same pattern as demographic restrictions.
- Boolean defaults to `false`, meaning no skill requirement is set until explicitly enabled.
- 8 boolean columns mirror the 8 skill areas from Phase 2 of the assessment.
- During eligibility computation, a skill group total ≥ 10 (out of max 30 = 5 questions × 6) qualifies the assessee for that skill. If the restriction is enabled for a ministry and the assessee meets the threshold, the ministry passes the skill filter.
- Copied from user ID=1 during new admin registration via `CopyDefaults`.

## References
- [Laravel Eloquent — Getting Started](https://laravel.com/docs/eloquent)
- [Laravel Eloquent — Mutators & Casts](https://laravel.com/docs/eloquent-mutators)
- [Laravel Eloquent — Scopes](https://laravel.com/docs/eloquent#local-scopes)
- [Laravel Eloquent — Relationships](https://laravel.com/docs/eloquent-relationships)
- [Laravel Eloquent — Observers](https://laravel.com/docs/eloquent#observers)
- [Laravel Eloquent — Serialization](https://laravel.com/docs/eloquent-serialization)
