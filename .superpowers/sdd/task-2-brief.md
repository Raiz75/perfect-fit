### Task 2: Update FrontendController to pass data

**Files:**
- Modify: `app/Http/Controllers/FrontendController.php`

**Context from Task 1:**
- Ministries now have a `description` TEXT column
- 29 ministries with descriptions seeded, keyed by frozen ID 1-29

**Requirements:**

Replace the `ministries()` method to load and pass the following data to the view:

1. `$categories` — All `MinistryCategory` with their `ministries` eager-loaded, ordered by ID
2. `$demographicRestrictions` — `DemographicRestriction` where `user_id = 1`, keyed by `ministry_id`
3. `$skillRestrictions` — `SkillRestriction` where `user_id = 1`, keyed by `ministry_id`
4. `$skills` — All `Skill`, ordered by ID

Use these imports at the top of the controller (replace the existing namespace-only block):

```php
use App\Models\DemographicRestriction;
use App\Models\MinistryCategory;
use App\Models\Skill;
use App\Models\SkillRestriction;
```

Read the existing file first at `app/Http/Controllers/FrontendController.php`.

The controller currently has `index()`, `ministries()` (returns empty view), and `privacyPolicy()` methods. Only modify `ministries()` and add the imports.

Implementation rules:
- Follow existing code conventions
- Do NOT add any comments
- Keep the other methods unchanged

**Verification:**
- The route `GET /ministries` calls `FrontendController::ministries()` (defined in `routes/web.php`)
- Visit `/ministries` after this change — it should render without errors (the view is still minimal but the data will be available)
