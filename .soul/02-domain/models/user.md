---
path: app/Models/User.php
type: model
table: users
fillable: [name, email, password, church_code, church_name]
hidden: [password, remember_token]
casts:
  email_verified_at: datetime
  password: hashed
traits: [HasFactory, Notifiable]
relationships:
  - HasMany: DemographicRestriction
  - HasMany: SkillRestriction
  - HasMany: SkillQuestion
  - HasMany: InterestAndPassionQuestion
  - HasMany: BehavioralQuestion
scopes: []
observers: []
status: draft
last_updated: 2026-07-28
---

# User

## Purpose
Represents a church admin who logs into the dashboard to manage restrictions, questions, and view assessment reports. Each admin has a unique 9-character `church_code` that assessees use to start the assessment.

## Relationships
- `demographicRestrictions()` — HasMany to `DemographicRestriction`
- `skillRestrictions()` — HasMany to `SkillRestriction`
- `skillQuestions()` — HasMany to `SkillQuestion`
- `interestAndPassionQuestions()` — HasMany to `InterestAndPassionQuestion`
- `behavioralQuestions()` — HasMany to `BehavioralQuestion`

## Notable behavior
- Admin user ID=1 is the template — `CopyDefaults` action duplicates all restriction and question records from user 1 to newly registered admins.
- `password` is hashed via Laravel's `hashed` cast.
- `church_code` is 9 characters, unique, generated during registration.

## References
- [Laravel Eloquent — Getting Started](https://laravel.com/docs/eloquent)
- [Laravel Eloquent — Mutators & Casts](https://laravel.com/docs/eloquent-mutators)
- [Laravel Eloquent — Scopes](https://laravel.com/docs/eloquent#local-scopes)
- [Laravel Eloquent — Relationships](https://laravel.com/docs/eloquent-relationships)
- [Laravel Eloquent — Observers](https://laravel.com/docs/eloquent#observers)
- [Laravel Eloquent — Serialization](https://laravel.com/docs/eloquent-serialization)
