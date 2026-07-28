---
path: app/Models/MinistryCategory.php
type: model
table: ministry_categories
fillable: [name]
hidden: []
casts: []
traits: []
relationships:
  - HasMany: Ministry
  - HasMany: InterestAndPassionQuestion
scopes: []
observers: []
status: draft
last_updated: 2026-07-28
---

# MinistryCategory

## Purpose
High-level grouping of church ministries (6 total). Used in Phase 3 (Interests & Passions) of the assessment where assessees rate their interest in each category.

## Relationships
- `ministries()` — HasMany to `Ministry`
- `interestAndPassionQuestions()` — HasMany to `InterestAndPassionQuestion`

## Notable behavior
- IDs are frozen (1-6) — seeded in `MinistryCategorySeeder` and referenced by `InterestAndPassionQuestion` foreign keys.

## References
- [Laravel Eloquent — Getting Started](https://laravel.com/docs/eloquent)
- [Laravel Eloquent — Mutators & Casts](https://laravel.com/docs/eloquent-mutators)
- [Laravel Eloquent — Scopes](https://laravel.com/docs/eloquent#local-scopes)
- [Laravel Eloquent — Relationships](https://laravel.com/docs/eloquent-relationships)
- [Laravel Eloquent — Observers](https://laravel.com/docs/eloquent#observers)
- [Laravel Eloquent — Serialization](https://laravel.com/docs/eloquent-serialization)
