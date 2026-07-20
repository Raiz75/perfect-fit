# Project Migration Roadmap — Perfit (Old → Laravel 12)

## Legend
| Icon | Meaning |
|------|---------|
| ✅ | Completed |
| 🔧 | In progress |
| ❌ | Not started |

---

## 1. Database Layer (✅ Complete)

### Migrations (10 files)

| Migration | Table | Status | Notes |
|-----------|-------|--------|-------|
| `0001_01_01_000000_create_users_table` | `users` | ✅ | Added `church_code` VARCHAR(9) UNIQUE, `church_name` nullable |
| `0001_01_01_000003_create_ministry_categories_table` | `ministry_categories` | ✅ | 6 categories: Core, Support, Outreach, Creative & Media, Care & Healing, Special Interest |
| `0001_01_01_000004_create_ministries_table` | `ministries` | ✅ | 29 ministries w/ FK → `ministry_categories` |
| `0001_01_01_000005_create_skills_table` | `skills` | ✅ | 8 skills lookup |
| `0001_01_01_000006_create_demographic_restrictions_table` | `demographic_restrictions` | ✅ | Per-admin per-ministry rules. UK(`user_id`, `ministry_id`) |
| `0001_01_01_000007_create_skill_restrictions_table` | `skill_restrictions` | ✅ | 8 boolean skill toggles per ministry. UK(`user_id`, `ministry_id`) |
| `0001_01_01_000008_create_skill_questions_table` | `skill_questions` | ✅ | 40 profiling Qs (5/skill). UK(`user_id`, `skill_id`, `question_number`) |
| `0001_01_01_000009_create_interest_and_passion_questions_table` | `interest_and_passion_questions` | ✅ | 30 Qs (5/category). UK(`user_id`, `ministry_category_id`, `question_number`) |
| `0001_01_01_000010_create_behavioral_questions_table` | `behavioral_questions` | ✅ | 145 Qs (5/ministry). UK(`user_id`, `ministry_id`, `question_number`) |
| `0001_01_01_000011_create_user_reports_table` | `user_reports` | ✅ | Volunteer assessment results |

**Key conventions used across all migrations:**
- `id()` for primary keys (bigIncrements)
- `foreignId(...)->constrained()->cascadeOnDelete()` for FK relationships
- `timestamps()` on every table
- Laravel snake_case table and column names

### Models (9 created)

| Model | Table | Fillable Fields | Relationships |
|-------|-------|-----------------|---------------|
| `User` | `users` | name, email, password, church_code, church_name | hasMany: demographicRestrictions, skillRestrictions, skillQuestions, interestAndPassionQuestions, behavioralQuestions |
| `MinistryCategory` | `ministry_categories` | name | hasMany: ministries, interestAndPassionQuestions |
| `Ministry` | `ministries` | name, ministry_category_id | belongsTo: category; hasMany: demographicRestrictions, skillRestrictions, behavioralQuestions |
| `Skill` | `skills` | name | hasMany: skillQuestions |
| `DemographicRestriction` | `demographic_restrictions` | user_id, ministry_id, gender, age_min, age_max, marital_status, baptized, time_in_faith | belongsTo: user, ministry |
| `SkillRestriction` | `skill_restrictions` | user_id, ministry_id, 8 boolean skills | belongsTo: user, ministry |
| `SkillQuestion` | `skill_questions` | user_id, skill_id, question_number, question_en, question_tl | belongsTo: user, skill |
| `InterestAndPassionQuestion` | `interest_and_passion_questions` | user_id, ministry_category_id, question_number, question_en, question_tl | belongsTo: user, ministryCategory |
| `BehavioralQuestion` | `behavioral_questions` | user_id, ministry_id, question_number, question_en, question_tl | belongsTo: user, ministry |
| `UserReport` | `user_reports` | church_code, email, name, contact_no, 8 skills, eligible_ministry, gender, age, marital_status, baptized, time_in_faith, time_of_submission | None (linked by church_code string) |

### Seeders (4 created)

| Seeder | Records | Purpose |
|--------|---------|---------|
| `MinistryCategorySeeder` | 6 | Core, Support, Outreach, Creative & Media, Care & Healing, Special Interest |
| `MinistrySeeder` | 29 | All ministries mapped to their categories (order must match old DB IDs 1-29 for references to work) |
| `SkillSeeder` | 8 | Music, Technology, Writing, Technical, Speaking, Accounting, Mentoring, Bible Knowledge |
| `DefaultDataSeeder` | 1 admin + 379 records | Creates admin user (ID=1, email=admin@admin, code=DEFAULT001), default restrictions (29 demo + 29 skill), 40 skill questions, 30 interest questions, 145 behavioral questions |

**Run order:** `php artisan migrate:fresh --seed`

**Important:** The `DefaultDataSeeder` inserts records with explicit IDs matching the old system:
- `ministry_id` 1-29 maps to the ministries in order (same as old database)
- `skill_id` 1-8 maps to skills in order
- `ministry_category_id` 1-6 maps to categories in order
- Admin user gets ID=1 (used as template when copying defaults to new admins)

---

## 2. Backend Implementation (❌ All Missing)

### 2.1 Controllers Needed

Create controllers under `app/Http/Controllers/`. Suggested structure:

```
app/Http/Controllers/
├── Auth/
│   ├── LoginController.php          # admin sign-in
│   ├── RegisterController.php       # admin sign-up (copies defaults from admin #1)
│   ├── ForgotPasswordController.php # send temp password via email
│   ├── ResetPasswordController.php  # update password
│   └── LogoutController.php         # destroy session
├── AssessmentController.php         # get all data by churchCode
├── ReportController.php             # create + filter + export reports
├── AdminPanelController.php         # load admin panel data
├── RestrictionController.php        # save/reset restrictions
├── QuestionController.php           # save/reset questions
├── SettingsController.php           # church name, change password
└── MinistryController.php           # ministry info page data
```

### 2.2 Old PHP → Laravel Endpoint Mapping

| Old PHP File | Method | Laravel Route | Suggested Controller Method | Notes |
|-------------|--------|---------------|---------------------------|-------|
| `php-signIn.php` | POST | `/admin/login` | `Auth\LoginController::login` | Use Laravel's built-in auth or custom session |
| `php-signUp.php` | POST | `/admin/register` | `Auth\RegisterController::register` | Must copy defaults from admin ID 1 (see `DefaultDataSeeder`) |
| `php-checkEmail.php` | POST | `/admin/check-email` | `Auth\RegisterController::checkEmail` | JSON response for async validation |
| `php-sendVerification.php` | POST | `/admin/send-verification` | `Auth\RegisterController::sendVerification` | Generate 6-digit code, send via mail |
| `php-forgotPass.php` | POST | `/admin/forgot-password` | `Auth\ForgotPasswordController::sendTempPassword` | Generate temp password, mail it |
| `php-validateChurchCode.php` | POST | `/admin/validate-church-code` | `Auth\RegisterController::validateChurchCode` | BINARY comparison for case-sensitive code |
| `php-sessionTest.php` | GET | `/admin/session-check` | `Auth\LoginController::checkSession` | Returns admin email or redirects |
| `php-logout.php` | POST | `/admin/logout` | `Auth\LogoutController::logout` | |
| `php-getDbData.php` | POST | `/api/assessment-data` | `AssessmentController::getData` | Returns all ministries, restrictions, questions by churchCode |
| `php-createUserReport.php` | POST | `/api/user-reports` | `ReportController::store` | Saves volunteer assessment |
| `php-adminFilter.php` | GET | `/admin/reports` | `ReportController::filter` | Returns filtered reports + counts |
| `php-generateAdminReport.php` | GET | `/admin/reports/export` | `ReportController::export` | Returns JSON for PDF generation |
| `php-loadAdminPanel.php` | GET | `/admin/panel-data` | `AdminPanelController::load` | All restrictions + questions for editing |
| `php-saveRestriction.php` | POST | `/admin/restrictions` | `RestrictionController::save` | Saves demographics, skills, questions edits |
| `php-resetRestriction.php` | POST | `/admin/restrictions/reset` | `RestrictionController::reset` | Resets to admin ID 1 defaults |
| `php-changeChurchName.php` | POST | `/admin/settings/church-name` | `SettingsController::updateChurchName` | |
| `php-updatePass.php` | POST | `/admin/settings/password` | `SettingsController::updatePassword` | |

### 2.3 Auth Implementation Notes

- Use **Laravel's session-based auth** (not API tokens) since this is a server-rendered app
- The old system used `$_SESSION['admin_email']` — replace with `Auth::user()`
- Middleware: create an `AdminMiddleware` for protected routes
- The old system has a special `admin@admin` account that cannot reset defaults (`noToAdmin` class in frontend shows/hides buttons)
- Registration must copy default restrictions/questions from admin ID 1 after creating a new user
- Church code must be 9-char alphanumeric, case-sensitive (use `BINARY` for lookups)

### 2.4 Service Classes to Create

```
app/Services/
├── AssessmentService.php       # Business logic for assessment phases
├── MinistryMatchingService.php # Rule-based filter for ministry matching
├── OpenAIService.php           # Server-side GPT-4o-mini integration
├── PDFExportService.php        # Report PDF generation
├── EmailService.php            # Verification codes, password resets
└── DefaultCopyService.php      # Copies defaults from admin #1 when registering
```

**Key details for `MinistryMatchingService.php`:**
- Phase 1-2 processing: demographic restrictions filter → skill restrictions filter
- Phase 3: Rank interest categories by score → filter ministries by top categories
- Phase 4: Show behavioral questions ONLY for filtered ministries
- Phase 5: Generate profile text via OpenAI

**Key details for `OpenAIService.php`:**
- **CRITICAL SECURITY:** Move API key from `callApi.js` (client-side) to `.env` as `OPENAI_API_KEY`
- Old code used `gpt-4o-mini` model — keep same model
- Prompt must generate a personalized ministry profile in English or Tagalog based on user's language
- Store API key in `config/services.php`

**Key details for `DefaultCopyService.php`:**
- On new admin registration, copy all records from `user_id = 1` to the new user:
  - `demographic_restrictions` (29 records)
  - `skill_restrictions` (29 records)  
  - `skill_questions` (40 records)
  - `interest_and_passion_questions` (30 records)
  - `behavioral_questions` (145 records)
- Use `replicate()` or raw `insert()` for performance

### 2.5 Email Integration

- Old system used **PHPMailer** with SMTP credentials in `php-config.php`
- **Laravel equivalent:** Use Laravel's built-in Mail facade with Gmail SMTP driver
- `.env` settings needed:
  ```
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.gmail.com
  MAIL_PORT=587
  MAIL_USERNAME=raizeningalla@gmail.com
  MAIL_PASSWORD=rdxf bmln oirv jshw
  MAIL_ENCRYPTION=tls
  ```
- Create Mailable classes: `VerificationCodeMail`, `TemporaryPasswordMail`
- Two email flows:
  1. **Sign-up verification:** Generate 6-digit code → send email → verify before creating account
  2. **Forgot password:** Generate temporary password → send email → immediate login

### 2.6 OpenAI Integration

- **Migrate from client-side to server-side** (security)
- Old API key in `callApi.js`: `***REMOVED***`
- Store in `.env`: `OPENAI_API_KEY=sk-proj-...`
- Install: `composer require openai-php/laravel`
- Use `gpt-4o-mini` model
- Prompt must include: user's skills, interest scores, behavioral responses, eligible ministries list
- Generate bilingual profile (English or Tagalog based on user's selected language)
- Profile output includes: calling summary, ministry fit description, spiritual gifts assessment
- Handle API errors gracefully with fallback text

---

## 3. Frontend Implementation (❌ All Missing)

### 3.1 Pages to Build

| Page | Route | Old File | Complexity | Notes |
|------|-------|----------|------------|-------|
| Landing | `/` | `index.html` | 🔶 Medium | Hero + modals + carousel + bible verse + how-it-works |
| Assessment | `/assessment` | `assessment.html` | 🔴 High | 5-phase wizard with localStorage persistence |
| Admin Login | `/admin/login` | `admin.html` | 🟢 Low | Login + signup + forgot password forms |
| Admin Dashboard | `/admin/dashboard` | `adminPanel.html` | 🔴 High | Charts, filters, report table, PDF export |
| Admin Restrictions | `/admin/restrictions` | `adminPanel.html` (section) | 🔶 Medium | Contenteditable tables |
| Admin Questions | `/admin/questions` | `adminPanel.html` (section) | 🔶 Medium | Contenteditable EN/TL tables |
| Admin Settings | `/admin/settings` | `adminPanel.html` (section) | 🟢 Low | Church name, code, password |
| Ministries Info | `/ministries` | `ministry.html` | 🟢 Low | Existing blade needs content |
| Privacy Policy | `/privacy-policy` | `privacyPolicy.html` | 🟢 Low | Static page |

### 3.2 Assessment Wizard (Highest Complexity)

The assessment is a **5-phase wizard** with the following flow:

```
Phase 1: Personal Details → Phase 2: Skill Profiling (40 Qs)
→ Phase 3: Interest & Passion (30 Qs) → Phase 4: Behavioral Profiling (variable Qs)
→ Phase 5: AI Results
```

**Critical features to replicate:**
1. **`localStorage` persistence** — each phase saves to localStorage on completion. On reload, detect saved data and offer to continue from last incomplete phase.
2. **Step counter** — visual progress indicator at top (9 dots + labels)
3. **Progress bar** — sticky bar at top showing percentage
4. **Puzzle animation** — puzzle pieces assemble per phase completion (4 pieces → complete image at phase 4)
5. **Bilingual UI** — toggle between English/Tagalog. All UI text is in `uiTranslation` object in `textContent(questions).js`
6. **Likert scale** — 6-option scale: Strongly Agree(6) → Strongly Disagree(1)
7. **Church code input** — retrieved from `localStorage.getItem("churchCode")`, falls back to a modal on landing page
8. **Session recovery** — check localStorage on load, show "Continue previous assessment?" prompt

**Data flow:**
```
Load: fetch(`/api/assessment-data?church_code=${code}`)
  → ministries, demographicRestrictions, skillRestrictions, skillQuestions, 
     interestAndPassionQuestions, behavioralQuestions

Phase 1 → save to localStorage("phase1"): { fName, fEmail, fContactNo, gender, age, status, baptized, timeInFaith }
Phase 2 → save to localStorage("phase2"): { music: [q1..q5], technology: [q6..q10], ... } (array of 40 scores)
Phase 3 → save to localStorage("phase3"): { category1: [q1..q5], ... } (array of 30 scores)
Phase 4 → save to localStorage("phase4"): { ministryId: [q1..q5], ... }
Phase 5 → POST /api/user-reports → POST /api/generate-profile → display charts + AI text → PDF export
```

**Ministry filtering logic (server-side or client-side):**
1. Start with ALL ministries
2. Filter by **interest categories**: rank 6 categories by average score, take top-scoring
3. Filter by **demographic restrictions**: gender, age range, marital status, baptized, time in faith
4. Filter by **skill requirements**: user must have all "Required" skills for a ministry
5. The remaining ministries = behavioral questions shown in Phase 4

### 3.3 Admin Dashboard (High Complexity)

**Components:**
1. **Charts (Chart.js + chartjs-plugin-datalabels):**
   - Gender distribution (pie)
   - Age groups (pie)
   - Time in Faith (bar)
   - Skills breakdown (bar)
   - Ministry eligibility (bar)
   - Baptized status (doughnut)
   - Marital status (bar)
2. **Filters:**
   - Search (name/email)
   - Date range
   - Demographics (gender, age, marital, baptized, faith)
   - Skills (8 checkboxes)
   - Ministries (29 checkboxes)
3. **Report table** — sortable, searchable
4. **PDF export** — use jsPDF + jspdf-autotable (or Laravel's barryvdh/laravel-dompdf)

### 3.4 Admin Restrictions/Questions Editor

- **Contenteditable tables** — inline editing without input fields
- **Demographics tab:** Radio buttons for gender/marital/faith, number inputs for age range, toggle for baptized
- **Skills tab:** Switch toggles for Required/Not Required per skill
- **Questions tabs (3):** Skill Questions, Interest & Passion, Behavioral — each with EN + TL editable cells
- **Save/Reset buttons:** Save sends all data to server; Reset restores from admin ID 1 defaults

### 3.5 Frontend Assets to Copy from Old Project

From `perfit-old/perfit/img/`:
```
icn-logo.png, logo.png, banner.png, bg.png, footer.png,
doveDynamic.gif, doveStatic.png, howItWorks.webp,
pzl-top.png, pzl-right.png, pzl-bottom.png, pzl-left.png,
icn-dashboard1.png, icn-dashboard2.png,
icn-restriction1.png, icn-restriction2.png,
icn-question1.png, icn-question2.png,
icn-settings1.png, icn-settings2.png,
icn-logout1.png, icn-logout2.png,
icn-userCount.png, icn-submissionCount.png,
icn-closedEyes.png, icn-openEyes.png,
icn-export1.png, icn-export2.png,
icn-find.png, icn-grow.png, icn-honesty.png,
el1.jpg, banner-cut.png
```

From `perfit-old/perfit/`:
- Copy images to `public/images/` (some already exist)
- Copy the ministry HTML description content from `ministry.html` for the ministry info page

### 3.6 Ministry Info Page Data

The `ministry.html` has detailed descriptions for each of the 29 ministries with:
- Ministry name header
- Descriptive paragraph (in English)
- Requirements table (demographic + skill restrictions pulled from DB)
- Carousel component

Suggestion: Create a `MinistryDescriptionsSeeder` or store descriptions in `resources/data/ministry-descriptions.php`.

---

## 4. Routes to Create

```php
// web.php

// Public routes
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/ministries', [FrontendController::class, 'ministries'])->name('ministries');
Route::get('/privacy-policy', [FrontendController::class, 'privacyPolicy'])->name('privacy-policy');

// Assessment (public)
Route::prefix('api')->group(function () {
    Route::post('/assessment-data', [AssessmentController::class, 'getData']);
    Route::post('/user-reports', [ReportController::class, 'store']);
    Route::post('/generate-profile', [AssessmentController::class, 'generateProfile']);
});

// Admin auth (guest)
Route::prefix('admin')->middleware('guest')->group(function () {
    Route::get('/login', [Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [Auth\LoginController::class, 'login']);
    Route::get('/register', [Auth\RegisterController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('/register', [Auth\RegisterController::class, 'register']);
    Route::post('/check-email', [Auth\RegisterController::class, 'checkEmail']);
    Route::post('/send-verification', [Auth\RegisterController::class, 'sendVerification']);
    Route::get('/forgot-password', [Auth\ForgotPasswordController::class, 'showForm']);
    Route::post('/forgot-password', [Auth\ForgotPasswordController::class, 'sendTempPassword']);
    Route::post('/validate-church-code', [Auth\RegisterController::class, 'validateChurchCode']);
});

// Admin panel (authenticated)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminPanelController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/panel-data', [AdminPanelController::class, 'load']);
    Route::get('/reports', [ReportController::class, 'filter']);
    Route::get('/reports/export', [ReportController::class, 'export']);
    Route::post('/restrictions', [RestrictionController::class, 'save']);
    Route::post('/restrictions/reset', [RestrictionController::class, 'reset']);
    Route::get('/session-check', [Auth\LoginController::class, 'checkSession']);
    Route::post('/logout', [Auth\LogoutController::class, 'logout']);
    Route::post('/settings/church-name', [SettingsController::class, 'updateChurchName']);
    Route::post('/settings/password', [SettingsController::class, 'updatePassword']);
});
```

---

## 5. Additional Configuration

### 5.1 `.env` additions
```
OPENAI_API_KEY=sk-proj-...
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=raizeningalla@gmail.com
MAIL_PASSWORD=rdxf bmln oirv jshw
MAIL_ENCRYPTION=tls
```

### 5.2 Composer packages
```bash
composer require openai-php/laravel       # OpenAI integration
composer require barryvdh/laravel-dompdf  # PDF generation (alt to jsPDF)
composer require laravel/socialite         # If adding OAuth later
```

### 5.3 NPM packages (frontend)
```bash
npm install chart.js chartjs-plugin-datalabels  # Dashboard charts
npm install jspdf jspdf-autotable               # PDF export (or use server-side DOMPDF)
npm install axios                                # HTTP client (already used per package.json)
```

---

## 6. Implementation Priority Order

### Phase 1: Foundation
1. ✅ Migrations & Seeders (DONE)
2. ❌ Create all Blade layouts (master layout with nav, footer)
3. ❌ Auth controllers + views (login/register/password reset)
4. ❌ Admin middleware

### Phase 2: Core Assessment
5. ❌ AssessmentController (data endpoint)
6. ❌ Ministry matching logic (MinistryMatchingService)
7. ❌ Assessment wizard frontend (5 phases, localStorage, bilingual)
8. ❌ Report creation + storage

### Phase 3: Admin Panel
9. ❌ Admin panel data loading
10. ❌ Dashboard with Chart.js (filters, charts, table)
11. ❌ Restriction editor (demographic + skills)
12. ❌ Question editor (3 question types)
13. ❌ Settings page
14. ❌ PDF export

### Phase 4: Enhancement
15. ❌ OpenAI profile generation (server-side)
16. ❌ Email notifications
17. ❌ Ministry info page (static content from old `ministry.html`)
18. ❌ Privacy policy page
19. ❌ Image assets copy

### Phase 5: Polish
20. ❌ CSRF protection (Laravel auto-handles via @csrf)
21. ❌ Input validation (Form Requests)
22. ❌ Error handling + user feedback
23. ❌ Session timeout handling
24. ❌ Responsive design testing

---

## 7. Data Reference Files

See `perfit-old/perfit/` for complete source reference:

| File | Contents |
|------|----------|
| `textContent(questions).js` | All 40 skill Qs, 30 interest Qs, 145 behavioral Qs (EN+TL), 28 Bible verses, UI translations, ministry category groupings |
| `callApi.js` | OpenAI API key (move to .env!) |
| `php-config.php` | DB + SMTP credentials |
| `adminPanel.html` | Full admin panel with all JS logic (charts, filters, editors) |
| `assessment.html` | Complete assessment wizard with all 5 phases |
| `index.html` | Landing page with hero, modals, carousel, verses |
| `ministry.html` | 29 ministry descriptions |
| `php-*.php` | Backend PHP endpoints (see endpoint mapping above) |

---

## 8. Important Implementation Notes

1. **BINARY comparison for church_code:** Use `WHERE BINARY church_code = ?` in raw queries or add a database-level binary collation
2. **Default admin template:** Admin ID 1 is the template — all data seeded by `DefaultDataSeeder` is what gets copied to new admins on registration
3. **admin@admin special user:** If email is `admin@admin`, hide the "Reset to default" buttons (frontend-only logic with `noToAdmin` class)
4. **Time of submission:** `user_reports.time_of_submission` stores server timestamp at report creation (use `now()` in controller, not Carbon)
5. **Eligible ministries:** Stored as comma-separated string in `eligible_ministry` column (for backward compatibility with old data)
6. **Age defaults:** Age min=1, max=99 when no restriction is set
7. **Baptized values:** 1 = "Yes/Baptized Only" (checkbox checked), 2 = "No/No Restriction" (checkbox unchecked) — not a boolean!
8. **Gender/marital values:** 0 = "No Restriction", 1 = "Male/Single", 2 = "Female/Married"
9. **Time in faith values:** 1 = "1+ Week", 2 = "6+ Months", 3 = "1+ Year", 4 = "2+ Years"
10. **Ministry ID mapping:** Ministries are indexed 1-29 and must stay in the same order as seeded — foreign keys depend on this order
