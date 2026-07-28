---
path: app/Http/Controllers/Admin/RestrictionController.php
type: controller
model: [DemographicRestriction, SkillRestriction]
methods: [demographics, skills, updateDemographics, updateSkills, resetDemographics, resetSkills]
routes:
  - name: admin.restrictions.demographics
    method: GET
    uri: /admin/restrictions/demographics
  - name: admin.restrictions.demographics.update
    method: POST
    uri: /admin/restrictions/demographics/update
  - name: admin.restrictions.demographics.reset
    method: POST
    uri: /admin/restrictions/demographics/reset
  - name: admin.restrictions.skills
    method: GET
    uri: /admin/restrictions/skills
  - name: admin.restrictions.skills.update
    method: POST
    uri: /admin/restrictions/skills/update
  - name: admin.restrictions.skills.reset
    method: POST
    uri: /admin/restrictions/skills/reset
middleware: [admin]
form_requests: []
resources_used: []
status: draft
last_updated: 2026-07-28
---

# RestrictionController

## Purpose
Manages ministry eligibility restrictions (demographic and skill-based) for the authenticated admin. Controls which ministries assessees qualify for based on their profile.

## Methods
- `demographics()` — Returns demographic restrictions management page.
- `skills()` — Returns skill restrictions management page.
- `updateDemographics(Request)` — Updates demographic restrictions for all ministries.
- `updateSkills(Request)` — Updates skill restrictions for all ministries.
- `resetDemographics()` — Resets demographic restrictions to defaults (from user ID=1).
- `resetSkills()` — Resets skill restrictions to defaults (from user ID=1).

## Authorization
Protected by `admin` middleware.

## References
- [Laravel Controllers](https://laravel.com/docs/controllers)
- [Laravel Controller Middleware](https://laravel.com/docs/controllers#controller-middleware)
- [Laravel Resource Controllers](https://laravel.com/docs/controllers#resource-controllers)
- [Laravel Dependency Injection](https://laravel.com/docs/controllers#dependency-injection-and-controllers)
