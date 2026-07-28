---
path: app/Models/DemographicRestriction.php
type: model
table: demographic_restrictions
fillable: [user_id, ministry_id, gender, age_min, age_max, marital_status, baptized, time_in_faith]
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

# DemographicRestriction

## Purpose
Defines demographic eligibility criteria (gender, age range, marital status, baptism status, time in faith) that an assessee must meet to be considered for a specific ministry. One record per user-ministry pair.

## Relationships
- `user()` — BelongsTo to `User`
- `ministry()` — BelongsTo to `Ministry`

## Notable behavior
- Unique constraint on `(user_id, ministry_id)` — each admin defines one demographic restriction per ministry.
- Default values are inclusive (age_min=1, age_max=99) so restrictions are opt-in exclusions.
- Field value mappings:
  - `gender`: 0=No Restriction, 1=Male, 2=Female
  - `marital_status`: 0=No Restriction, 1=Single, 2=Married
  - `baptized`: 1=Baptized Only, 2=No Restriction (NOT a boolean)
  - `time_in_faith`: 1=1+ Week, 2=6+ Months, 3=1+ Year, 4=2+ Years
- Copied from user ID=1 during new admin registration via `CopyDefaults`.

## References
- [Laravel Eloquent — Getting Started](https://laravel.com/docs/eloquent)
- [Laravel Eloquent — Mutators & Casts](https://laravel.com/docs/eloquent-mutators)
- [Laravel Eloquent — Scopes](https://laravel.com/docs/eloquent#local-scopes)
- [Laravel Eloquent — Relationships](https://laravel.com/docs/eloquent-relationships)
- [Laravel Eloquent — Observers](https://laravel.com/docs/eloquent#observers)
- [Laravel Eloquent — Serialization](https://laravel.com/docs/eloquent-serialization)
