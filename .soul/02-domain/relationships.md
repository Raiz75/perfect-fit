---
type: erd-summary
models_covered: [User, Ministry, MinistryCategory, Skill, DemographicRestriction, SkillRestriction, SkillQuestion, InterestAndPassionQuestion, BehavioralQuestion, UserReport]
relationship_count: 14
polymorphic_relations: []
last_updated: 2026-07-28
---

# Domain Relationships

```
MinistryCategory --HasMany--> Ministry
MinistryCategory --HasMany--> InterestAndPassionQuestion

Ministry --HasMany--> DemographicRestriction
Ministry --HasMany--> SkillRestriction
Ministry --HasMany--> BehavioralQuestion
Ministry --BelongsTo--> MinistryCategory

Skill --HasMany--> SkillQuestion

User --HasMany--> DemographicRestriction
User --HasMany--> SkillRestriction
User --HasMany--> SkillQuestion
User --HasMany--> InterestAndPassionQuestion
User --HasMany--> BehavioralQuestion

DemographicRestriction --BelongsTo--> User
DemographicRestriction --BelongsTo--> Ministry

SkillRestriction --BelongsTo--> User
SkillRestriction --BelongsTo--> Ministry

SkillQuestion --BelongsTo--> User
SkillQuestion --BelongsTo--> Skill

InterestAndPassionQuestion --BelongsTo--> User
InterestAndPassionQuestion --BelongsTo--> MinistryCategory

BehavioralQuestion --BelongsTo--> User
BehavioralQuestion --BelongsTo--> Ministry
```

**UserReport** has no relationships defined (standalone model for storing submitted assessment results).

## Polymorphic Relations
None found.

## References
- [Laravel Eloquent Relationships](https://laravel.com/docs/eloquent-relationships)
- [Laravel Polymorphic Relations](https://laravel.com/docs/eloquent-relationships#polymorphic-relationships)
- [Laravel Many-to-Many](https://laravel.com/docs/eloquent-relationships#many-to-many)
- [Laravel Querying Relations](https://laravel.com/docs/eloquent-relationships#querying-relations)
