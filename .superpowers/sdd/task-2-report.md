# Task 2 Report — FrontendController data wiring

## What was modified

**`app/Http/Controllers/FrontendController.php`**

- Added imports: `DemographicRestriction`, `MinistryCategory`, `Skill`, `SkillRestriction`
- Replaced `ministries()` — now loads:
  - `$categories`: all `MinistryCategory` with `ministries` eager-loaded, ordered by ID
  - `$demographicRestrictions`: `DemographicRestriction` where `user_id = 1`, keyed by `ministry_id`
  - `$skillRestrictions`: `SkillRestriction` where `user_id = 1`, keyed by `ministry_id`
  - `$skills`: all `Skill`, ordered by ID
- All four passed via `compact()` to `view('ministries')`
- Other methods (`index`, `privacyPolicy`) unchanged

## Command output: `php artisan route:list`

Ran successfully — no syntax errors. The `ministries` route is confirmed registered:

```
GET|HEAD  ministries  …  ministries › FrontendController@ministries
```

## Result

**DONE** — `php artisan route:list` passes with 53 routes. No concerns.
