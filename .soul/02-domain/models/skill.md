---
path: app/Models/Skill.php
type: model
table: skills
fillable: [name]
hidden: []
casts: []
traits: []
relationships:
  - HasMany: SkillQuestion
scopes: []
observers: []
status: draft
last_updated: 2026-07-28
---

# Skill

## Purpose
Represents one of 8 skill areas assessed in Phase 2: Music, Technology, Writing, Technical, Speaking, Accounting, Mentoring, Bible Knowledge.

## Relationships
- `skillQuestions()` — HasMany to `SkillQuestion`

## Notable behavior
- IDs are frozen (1-8) — seeded in `SkillSeeder` and referenced by `SkillQuestion` foreign keys.

## References
- [Laravel Eloquent — Getting Started](https://laravel.com/docs/eloquent)
- [Laravel Eloquent — Mutators & Casts](https://laravel.com/docs/eloquent-mutators)
- [Laravel Eloquent — Scopes](https://laravel.com/docs/eloquent#local-scopes)
- [Laravel Eloquent — Relationships](https://laravel.com/docs/eloquent-relationships)
- [Laravel Eloquent — Observers](https://laravel.com/docs/eloquent#observers)
- [Laravel Eloquent — Serialization](https://laravel.com/docs/eloquent-serialization)
