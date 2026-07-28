---
path: app/Models/Ministry.php
type: model
table: ministries
fillable: [name, ministry_category_id]
hidden: []
casts: []
traits: []
relationships:
  - BelongsTo: MinistryCategory
  - HasMany: DemographicRestriction
  - HasMany: SkillRestriction
  - HasMany: BehavioralQuestion
scopes: []
observers: []
status: draft
last_updated: 2026-07-28
---

# Ministry

## Purpose
Represents a specific church ministry role (e.g. Worship, Ushering, Children's Ministry — 29 total). Ministries are grouped under `MinistryCategory` and are the target entities being assessed for fit.

## Relationships
- `category()` — BelongsTo to `MinistryCategory` (foreign key: `ministry_category_id`)
- `demographicRestrictions()` — HasMany to `DemographicRestriction`
- `skillRestrictions()` — HasMany to `SkillRestriction`
- `behavioralQuestions()` — HasMany to `BehavioralQuestion`

## Notable behavior
- IDs are frozen (1-29) — seeded in `MinistrySeeder` and referenced by foreign keys in restrictions and questions.
- `ministry_category_id` is nullable with `ON DELETE SET NULL`.

## References
- [Laravel Eloquent — Getting Started](https://laravel.com/docs/eloquent)
- [Laravel Eloquent — Mutators & Casts](https://laravel.com/docs/eloquent-mutators)
- [Laravel Eloquent — Scopes](https://laravel.com/docs/eloquent#local-scopes)
- [Laravel Eloquent — Relationships](https://laravel.com/docs/eloquent-relationships)
- [Laravel Eloquent — Observers](https://laravel.com/docs/eloquent#observers)
- [Laravel Eloquent — Serialization](https://laravel.com/docs/eloquent-serialization)
