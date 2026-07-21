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

## 2. Backend Implementation (✅ Complete)

### 2.1 Controllers

```
app/Http/Controllers/
├── FrontendController.php           # ✅ Landing, ministries, privacy-policy pages
├── Auth/
│   ├── LoginController.php          # ✅ Uses LoginRequest Form Request
│   ├── RegisterController.php       # ✅ Uses Form Requests + CopyDefaults action
│   ├── ForgotPasswordController.php # ✅ send temp password via email
│   └── LogoutController.php         # ✅ Redirects to login page on logout
├── Admin/
│   ├── DashboardController.php      # ✅ Full dashboard with filters, charts, table
│   ├── RestrictionController.php    # ✅ Save/reset restrictions
│   ├── QuestionController.php       # ✅ Save/reset questions
│   └── SettingsController.php       # ✅ Uses ChangePasswordRequest Form Request
```

### 2.2 Old PHP → Laravel Endpoint Mapping

| Old PHP File | Method | Laravel Route | Controller Method | Status | Notes |
|-------------|--------|---------------|-------------------|--------|-------|
| `php-signIn.php` | POST | `/admin/login` | `Auth\LoginController::login` | ✅ | Uses LoginRequest Form Request |
| `php-signUp.php` | POST | `/admin/register` | `Auth\RegisterController::register` | ✅ | Uses RegisterRequest Form Request + CopyDefaults Action |
| `php-checkEmail.php` | POST | `/admin/check-email` | `Auth\RegisterController::checkEmail` | ✅ | Uses CheckEmailRequest Form Request |
| `php-sendVerification.php` | POST | `/admin/send-verification` | `Auth\RegisterController::sendVerification` | ✅ | Sends VerificationCodeMail |
| `php-forgotPass.php` | POST | `/admin/forgot-password` | `Auth\ForgotPasswordController::sendTempPassword` | ✅ | Sends TemporaryPasswordMail |
| `php-validateChurchCode.php` | POST | `/admin/validate-church-code` | `Auth\RegisterController::validateChurchCode` | ✅ | BINARY comparison |
| — | POST | `/admin/verify-code` | `Auth\RegisterController::verifyCode` | ✅ | Server-side code verification |
| `php-sessionTest.php` | GET | `/admin/session-check` | `Auth\LoginController::checkSession` | ✅ | Returns admin or 401 |
| `php-logout.php` | POST | `/admin/logout` | `Auth\LogoutController::logout` | ✅ | Redirects to admin.login |
| `php-getDbData.php` | POST | `/api/assessment-data` | `AssessmentController::getData` | ❌ | Phase 2 |
| `php-createUserReport.php` | POST | `/api/user-reports` | `ReportController::store` | ❌ | Phase 2 |
| `php-adminFilter.php` | GET | `/admin/dashboard/data` | `Admin\DashboardController::getData` | ✅ | Full filter support (search, date, demo, skills, ministries) |
| `php-generateAdminReport.php` | GET | `/admin/reports/export` | `ReportController::export` | ❌ | Phase 3 |
| `php-loadAdminPanel.php` | GET | `/admin/panel-data` | `AdminPanelController::load` | ❌ | Phase 3 |
| `php-saveRestriction.php` | POST | `/admin/restrictions` | `RestrictionController::save` | ❌ | Phase 3 |
| `php-resetRestriction.php` | POST | `/admin/restrictions/reset` | `RestrictionController::reset` | ❌ | Phase 3 |
| `php-changeChurchName.php` | POST | `/admin/settings/church-name` | `Admin\SettingsController::updateChurchName` | ✅ | Unique check excluding self |
| `php-updatePass.php` | POST | `/admin/settings/password` | `Admin\SettingsController::updatePassword` | ✅ | Min 8 + 1 capital + 1 number + 1 special, uses ChangePasswordRequest |

### 2.3 Auth Implementation Notes

- ✅ Uses **Laravel's session-based auth** (not API tokens)
- ✅ Old `$_SESSION['admin_email']` replaced with `Auth::user()`
- ✅ `AdminMiddleware` created — registered as `admin` alias in `bootstrap/app.php`
- ❌ `admin@admin` special user logic (hide "Reset" buttons) — frontend only, Phase 3
- ✅ Registration copies defaults from admin ID 1 via `app/Actions/CopyDefaults.php`
- ✅ Church code 9-char, case-sensitive, `BINARY` comparison in `validateChurchCode()`
- ✅ Password strength: min 8 chars, 1 uppercase, 1 number, 1 special (server-side + client-side validation)
- ✅ Form Request classes created: `LoginRequest`, `RegisterRequest`, `CheckEmailRequest`, `SendVerificationRequest`, `ChangePasswordRequest`

### 2.4 Service / Action Classes

```
app/Actions/
└── CopyDefaults.php        # ✅ Extracted from RegisterController — copies default records from admin ID 1 to new user

app/Services/ (pending)
├── AssessmentService.php       # ❌ Business logic for assessment phases — Phase 2
├── MinistryMatchingService.php # ❌ Rule-based filter for ministry matching — Phase 2
├── OpenAIService.php           # ❌ Server-side GPT-4o-mini integration — Phase 4
└── PDFExportService.php        # ❌ Report PDF generation — Phase 3
```

**Note:** Email sending is handled directly via Laravel Mailables (`app/Mail/`) — no separate EmailService needed.

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

### 2.5 Email Integration (✅ Complete)

- ✅ Uses **Laravel's built-in Mail facade** (no PHPMailer)
- ✅ `.env` configured with Gmail SMTP credentials
- ✅ `config/mail.php` — added `encryption` key to smtp mailer
- ✅ Mailable classes created:
  - `app/Mail/VerificationCodeMail.php` → `resources/views/emails/verification-code.blade.php`
  - `app/Mail/TemporaryPasswordMail.php` → `resources/views/emails/temporary-password.blade.php`
- ✅ Two email flows working:
  1. **Sign-up:** Generate 6-digit code → email → server-side verify via `/admin/verify-code` → create account
  2. **Forgot password:** Generate temp password → email → immediate login with temp password

### 2.6 OpenAI Integration

- **Migrate from client-side to server-side** (security)
- ~~Old API key in `callApi.js`~~ (removed from history)
- Store in `.env`: `OPENAI_API_KEY=sk-proj-...`
- Install: `composer require openai-php/laravel`
- Use `gpt-4o-mini` model
- Prompt must include: user's skills, interest scores, behavioral responses, eligible ministries list
- Generate bilingual profile (English or Tagalog based on user's selected language)
- Profile output includes: calling summary, ministry fit description, spiritual gifts assessment
- Handle API errors gracefully with fallback text

---

## 3. Frontend Implementation (✅ Complete)

### 3.1 Pages Built

| Page | Route | Old File | Status | Notes |
|------|-------|----------|--------|-------|
| Landing | `/` | `index.html` | ✅ | Refactored to `@extends('_layouts.master')`. Hero + how-it-works timeline + ministry carousel + 4 modals (user type, church code, language, bible verse) + dove trigger. All buttons wired up. |
| Assessment | `/assessment` | `assessment.html` | ❌ | Phase 2 |
| Admin Login | `/admin/login` | `admin.html` | ✅ | Standalone page (no admin layout/sidebar). Login/signup sliding forms + verify popup + forgot password modal. JS extracted to `resources/js/admin-login.js`. Password strength validation client-side. |
| Admin Dashboard | `/admin/dashboard` | `adminPanel.html` | ✅ | Full implementation with 7 Chart.js charts, filters (search, date, demographic, skills, ministries), and report table. Data endpoint: `/admin/dashboard/data`. JS: `resources/js/admin-dashboard.js`. |
| Admin Restrictions | `/admin/restrictions` | `adminPanel.html` | ✅ | Top nav with 2 tabs (Demographics, Skills). Full CRUD with save/reset. |
| Admin Questions | `/admin/questions` | `adminPanel.html` | ✅ | 3 tab pages (Skill, Interest & Passion, Behavioral). Inline editable cells. Save/reset with confirmation modal. |
| Admin Settings | `/admin/settings` | `adminPanel.html` | ✅ | Church name save + church code copy + change password modal (custom overlay). Password rules: min 8 chars, 1 capital, 1 number, 1 special. Uses ChangePasswordRequest. |
| Ministries Info | `/ministries` | `ministry.html` | ❌ | Phase 4 (view file exists, empty) |
| Privacy Policy | `/privacy-policy` | `privacyPolicy.html` | 🔧 | Placeholder (Phase 4 for full content) |

### 3.2 Assessment Wizard (Highest Complexity — Phase 2)

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

### 3.3 Admin Layout & Sidebar (✅ Complete)

- ✅ **Admin layout** (`_layouts/admin.blade.php`) — full page wrapper with `<x-admin-sidebar />` + `<x-admin-top-nav />` + mobile hamburger + overlay. Sidebar JS extracted to `resources/js/admin.js` (loaded via Vite).
- ✅ **Sidebar component** (`components/admin-sidebar.blade.php`) — class-backed `<x-admin-sidebar />` component. Fixed left sidebar, toggles between 250px (expanded) and 70px (icon-only).
  - Nav links: Dashboard, Restriction Editor, Question Editor, Settings (all using route names)
  - Logout confirmation modal (glassmorphism, asks "Are you sure?")
  - Active link highlighted with purple left border
  - Uses **Tabler icons** (`@tabler/icons-webfont`) instead of image files
  - Desktop: toggle chevron button. Mobile: hamburger + overlay.
- ✅ **Top nav component** (`components/admin-top-nav.blade.php`) — class-backed `<x-admin-top-nav />` component. Sticky top bar with page title via `@yield('pageTitle')`. Hamburger visible on mobile.
- ✅ **Login page made standalone** — no sidebar, no admin wrapper
- ✅ **Admin layout structure** updated: `.adminPage` → `.adminSidebar` + `.adminRight` (`.adminTopNav` + `.adminContent`)

### 3.4 Admin Dashboard (✅ Complete)

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
3. **Report table** — scrollable with sticky headers, auto-renders on filter change
4. **Data endpoint** — `GET /admin/dashboard/data` with all filter params

### 3.5 Admin Restrictions/Questions Editor (✅ Complete)

- **Contenteditable tables** — inline editing without input fields
- **Demographics tab:** Radio buttons for gender/marital/faith, number inputs for age range, toggle for baptized
- **Skills tab:** Switch toggles for Required/Not Required per skill
- **Questions tabs (3):** Skill Questions, Interest & Passion, Behavioral — each with EN + TL editable cells
- **Save/Reset buttons:** Save sends all data to server; Reset restores from admin ID 1 defaults with confirmation modal

### 3.6 Coding Convention Compliance (✅ Applied)

- **Blade components** — `@include('_partials.adminSide.sideNav')` → `<x-admin-sidebar />` and `<x-admin-top-nav />` (class-backed)
- **Form Requests** — All auth actions use dedicated Form Request classes in `app/Http/Requests/Auth/`
- **Action classes** — `app/Actions/CopyDefaults.php` extracted from `RegisterController`
- **Inline JS extracted** — Admin sidebar to `admin.js`, login logic to `admin-login.js`, dashboard to `admin-dashboard.js`
- **Tabler Icons** — All inline SVGs replaced with `ti ti-*` classes from `@tabler/icons-webfont`
- **Password rules** — Min 8, uppercase, number, special (server + client side)

### 3.7 Frontend Assets

**Images in `public/images/` (current state):**
```
icn-logo.png, logo.png, banner.png, doveStatic.png    ← 4 files kept
```
- All icon images replaced with **Tabler icons** (`@tabler/icons-webfont`)
- Unused images removed: `bg.png`, `icn-closedEyes.png`, `icn-openEyes.png`, `icn-dashboard*`, `icn-restriction*`, `icn-question*`, `icn-settings*`, `icn-logout*`

**Still to copy from old project when needed:**
- `footer.png`, `howItWorks.webp`, `pzl-*` (puzzle pieces for assessment), `icn-userCount.png`, `icn-submissionCount.png`, `icn-export*`, `icn-find.png`, `icn-grow.png`, `icn-honesty.png`, `el1.jpg`, `banner-cut.png`

**Landing page icons:** Inline SVGs replaced with Tabler icon classes (`ti ti-users`, `ti ti-key`, `ti ti-language`, `ti ti-clipboard-text`, `ti ti-send`, `ti ti-chart-bar`, `ti ti-chevron-*`)

### 3.8 Ministry Info Page Data

The `ministry.html` has detailed descriptions for each of the 29 ministries with:
- Ministry name header
- Descriptive paragraph (in English)
- Requirements table (demographic + skill restrictions pulled from DB)
- Carousel component

Suggestion: Create a `MinistryDescriptionsSeeder` or store descriptions in `resources/data/ministry-descriptions.php`.

---

## 4. Routes (Actual Current State)

```php
// routes/web.php — Current state (run php artisan route:list to see all)

// Public routes (✅ 3 routes)
Route::get('/',                         [FrontendController::class, 'index'])->name('home');
Route::get('/ministries',               [FrontendController::class, 'ministries'])->name('ministries');
Route::get('/privacy-policy',           [FrontendController::class, 'privacyPolicy'])->name('privacy-policy');

// Admin auth — no middleware (guest access) (✅ 8 routes)
Route::prefix('admin')->group(function () {
    GET  /login                           Auth\LoginController@showLoginForm         admin.login
    POST /login                           Auth\LoginController@login
    POST /check-email                     Auth\RegisterController@checkEmail
    POST /send-verification               Auth\RegisterController@sendVerification
    POST /verify-code                     Auth\RegisterController@verifyCode
    POST /register                        Auth\RegisterController@register
    POST /validate-church-code            Auth\RegisterController@validateChurchCode
    POST /forgot-password                 Auth\ForgotPasswordController@sendTempPassword
    GET  /session-check                   Auth\LoginController@checkSession
});

// Admin panel — middleware('admin') (✅ 15 routes)
Route::prefix('admin')->middleware('admin')->group(function () {
    GET  /dashboard                        Admin\DashboardController@index            admin.dashboard
    GET  /dashboard/data                   Admin\DashboardController@getData          admin.dashboard.data
    GET  /restrictions                     (redirect)                                 admin.restrictions
    GET  /restrictions/demographics        Admin\RestrictionController@demographics   admin.restrictions.demographics
    POST /restrictions/demographics/update Admin\RestrictionController@updateDemographics  admin.restrictions.demographics.update
    POST /restrictions/demographics/reset  Admin\RestrictionController@resetDemographics   admin.restrictions.demographics.reset
    GET  /restrictions/skills              Admin\RestrictionController@skills         admin.restrictions.skills
    POST /restrictions/skills/update       Admin\RestrictionController@updateSkills       admin.restrictions.skills.update
    POST /restrictions/skills/reset        Admin\RestrictionController@resetSkills        admin.restrictions.skills.reset
    GET  /questions                        (redirect)                                 admin.questions
    GET  /questions/skill                  Admin\QuestionController@skill             admin.questions.skill
    POST /questions/skill/update           Admin\QuestionController@updateSkill       admin.questions.skill.update
    POST /questions/skill/reset            Admin\QuestionController@resetSkill        admin.questions.skill.reset
    GET  /questions/interest               Admin\QuestionController@interest          admin.questions.interest
    POST /questions/interest/update        Admin\QuestionController@updateInterest    admin.questions.interest.update
    POST /questions/interest/reset         Admin\QuestionController@resetInterest     admin.questions.interest.reset
    GET  /questions/behavioral             Admin\QuestionController@behavioral        admin.questions.behavioral
    POST /questions/behavioral/update      Admin\QuestionController@updateBehavioral  admin.questions.behavioral.update
    POST /questions/behavioral/reset       Admin\QuestionController@resetBehavioral   admin.questions.behavioral.reset
    GET  /settings                         Admin\SettingsController@index             admin.settings
    POST /settings/church-name             Admin\SettingsController@updateChurchName  admin.settings.church-name
    POST /settings/password                Admin\SettingsController@updatePassword    admin.settings.password
    POST /logout                           Auth\LogoutController@logout               admin.logout
});

// ❌ Still needed (Phase 2):
// api/assessment-data, api/user-reports, api/generate-profile
```

---

## 5. Additional Configuration

### 5.1 `.env` — Mail (✅ Done)
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=raizeningalla@gmail.com
MAIL_PASSWORD="rdxf bmln oirv jshw"          ← quoted because of spaces
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="raizeningalla@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5.2 `.env` — Still needed
```
OPENAI_API_KEY=sk-proj-...                    # Phase 4
```

### 5.3 Composer packages
```bash
# Already installed: none needed for mail (Laravel built-in)
composer require openai-php/laravel       # ❌ Phase 4 — OpenAI integration
composer require barryvdh/laravel-dompdf  # ❌ Phase 3 — PDF generation
composer require laravel/socialite         # ❌ Maybe later
```

### 5.4 NPM packages
```bash
npm install chart.js chartjs-plugin-datalabels  # ✅ Installed — Dashboard charts
npm install jspdf jspdf-autotable               # ❌ Phase 3 — PDF export (or use server-side DOMPDF)
npm install axios                                # ✅ Already in package.json
npm install @tabler/icons-webfont                # ✅ Installed — replaced all icon images with Tabler icons
```

---

## 6. Implementation Priority Order

### Phase 1: Foundation (✅ Complete)
1. ✅ Migrations & Seeders
2. ✅ Master layout (`_layouts/master.blade.php`) with nav + footer
3. ✅ Auth controllers + views (login/register/forgot-password with sliding forms + modals)
4. ✅ Admin middleware + route registration
5. ✅ Email integration (Laravel Mail + Gmail SMTP + Mailable classes + email Blade views)
6. ✅ FrontendController + public routes (landing, ministries, privacy-policy)
7. ✅ Landing page modals (user type, church code, language, bible verse) — all buttons wired

### Phase 2: Core Assessment (❌ Not started)
8. ❌ AssessmentController (data endpoint)
9. ❌ Ministry matching logic (MinistryMatchingService)
10. ❌ Assessment wizard frontend (5 phases, localStorage, bilingual)
11. ❌ Report creation + storage

### Phase 3: Admin Panel (✅ Complete)
12. ✅ Dashboard with Chart.js (filters: search, date, demographics, skills, ministries; 7 charts: gender, age, faith, skills, ministry, baptized, marital; report table)
13. ✅ Restriction editor (demographic + skills with save/reset)
14. ✅ Question editor (3 question types with contenteditable tables + save/reset)
15. ✅ Settings page (church name, church code copy, change password with validation)
16. ❌ PDF export

### Phase 4: Enhancement
17. ❌ OpenAI profile generation (server-side)
18. ❌ Ministry info page (static content from old `ministry.html`)
19. ❌ Privacy policy page (full content)
20. ❌ Image assets copy (remaining images)

### Phase 5: Polish (✅ In Progress)
21. ✅ Form Request validation — LoginRequest, RegisterRequest, CheckEmailRequest, SendVerificationRequest, ChangePasswordRequest
22. ✅ Coding conventions applied — Blade components, Action classes, inline JS/CSS extracted, Tabler icons, naming conventions
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
11. **Password strength rules:** Min 8 characters, at least 1 uppercase letter, 1 number, and 1 special character — enforced both client-side (admin-login.js) and server-side (RegisterRequest, ChangePasswordRequest)
12. **Vite entry points:** `admin.js` (sidebar toggle), `admin-login.js` (auth forms), `admin-dashboard.js` (Chart.js dashboard) — all registered in `vite.config.js`