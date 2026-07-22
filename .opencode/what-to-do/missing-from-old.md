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
| `php-signIn.php` | POST | `/admin/login` | `Auth\LoginController::login` | ✅ | Standard form POST → redirect. Uses LoginRequest. JS enhancement only (togglePassword, toast). |
| `php-signUp.php` | POST | `/admin/send-verification` | `Auth\RegisterController::sendVerification` | ✅ | Standard form POST → redirect to `/admin/register?verify=` with toast. Validates via SendVerificationRequest (email unique + password rules). Stores email+password in session. |
| — | POST | `/admin/verify-registration` | `Auth\RegisterController::verifyRegistration` | ✅ | New endpoint — validates code from session, creates user from stored session data, clears session, redirects to login. Merges old verify-code + register flow. |
| `php-forgotPass.php` | POST | `/admin/forgot-password` | `Auth\ForgotPasswordController::sendTempPassword` | ✅ | Standard form POST → redirect back with toast flash. Sends TemporaryPasswordMail. |
| `php-validateChurchCode.php` | POST | `/admin/validate-church-code` | `Auth\RegisterController::validateChurchCode` | ✅ | BINARY comparison (kept as JSON endpoint for assessment page) |
| `php-logout.php` | POST | `/admin/logout` | `Auth\LogoutController::logout` | ✅ | Redirects to admin.login |
| `php-getDbData.php` | POST | `/api/assessment-data` | Replaced by session-based `AssessmentController::show()` | 🔧 | Handled server-side via session (church_code stored in session, not fetched via API) |
| `php-createUserReport.php` | POST | `/api/user-reports` | `ReportController::store` | ❌ | Phase 3 |
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
- ✅ `admin@admin` special user logic (hide "Reset" buttons) — frontend only, Phase 3
- ✅ Registration copies defaults from admin ID 1 via `app/Actions/CopyDefaults.php`
- ✅ Church code 9-char, case-sensitive, `BINARY` comparison in `validateChurchCode()`
- ✅ Password strength: min 8 chars, 1 uppercase, 1 number, 1 special (server-side only via Form Requests — removed from JS)
- ✅ **All POSTs handled by controllers** — sign-in, send-verification, verify-registration, forgot-password all use standard `<form method="POST">` submissions. JS has zero fetch calls.
- ✅ **Login and Register are separate pages** — `/admin/login` and `/admin/register` each with their own Blade template
- ✅ **Verification flow**: `sendVerification` stores email+password in session, redirects to `/admin/register?verify=`. JS auto-shows verify modal. `verifyRegistration` reads session, creates user, clears session.
- ✅ **Messages go to toast component** — all server messages (validation errors, success flashes) pass through `#toast-data` hidden element → dispatched as `notify` CustomEvent → rendered by `⚡toast-message` Livewire component. No inline alert boxes.
- ✅ Form Request classes created: `LoginRequest`, `SendVerificationRequest`, `ChangePasswordRequest`

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
| Assessment | `/assessment` | `assessment.html` | ✅ | All 4 phases built as server-side POST forms. No Livewire, no JS submissions. Session-based. Puzzle animation. Dynamic step counter. |
| Admin Login | `/admin/login` | `admin.html` | ✅ | Standalone page (no admin layout/sidebar). Sign-in form only + forgot password modal. Links to `/admin/register`. JS: `resources/js/auth.js`. All messages via toast component. All POSTs go to controllers (standard form submissions). |
| Admin Register | `/admin/register` | `admin.html` | ✅ | Standalone page. Sign-up form + email verification modal (auto-shown on `?verify=`). Links to `/admin/login`. JS: `resources/js/auth.js`. Same toast + standard form POST pattern. |
| Admin Dashboard | `/admin/dashboard` | `adminPanel.html` | ✅ | Full implementation with 7 Chart.js charts, filters (search, date, demographic, skills, ministries), and report table. Data endpoint: `/admin/dashboard/data`. JS: `resources/js/admin-dashboard.js`. Numeric mappings moved from JS to DashboardController. Ministry list served from PHP constant. Ministry checkboxes rendered server-side. |
| Admin Restrictions | `/admin/restrictions` | `adminPanel.html` | ✅ | Top nav with 2 tabs (Demographics, Skills). Full CRUD with save/reset. |
| Admin Questions | `/admin/questions` | `adminPanel.html` | ✅ | 3 tab pages (Skill, Interest & Passion, Behavioral). Inline editable cells. Save/reset with confirmation modal. |
| Admin Settings | `/admin/settings` | `adminPanel.html` | ✅ | Church name save + church code copy + change password modal (custom overlay). Password rules: min 8 chars, 1 capital, 1 number, 1 special. Uses ChangePasswordRequest. |
| Ministries Info | `/ministries` | `ministry.html` | ❌ | Phase 4 (view file exists, empty) |
| Privacy Policy | `/privacy-policy` | `privacyPolicy.html` | 🔧 | Placeholder (Phase 4 for full content) |

### 3.2 Assessment Wizard (✅ Phases 1-4 Complete)

The assessment is a **5-phase wizard** rebuilt from scratch — **no Livewire, no JS for submissions** (pure HTML form POSTs to the controller).

```
Phase 1: Personal Details   → POST /assessment/phase1 → session → Phase 2
Phase 2: Skill Profiling    → POST /assessment/phase2 → session → Phase 3
Phase 3: Interest & Passion → POST /assessment/phase3 → session → Phase 4
Phase 4: Behavioral         → POST /assessment/phase4 → session → Phase 5
Phase 5: AI Results         (not yet built)
```

**Architecture:**
- `AssessmentController` at `app/Http/Controllers/Assessment/AssessmentController.php`
- Church code set via `POST /assessment/set-church-code` → stored in `session('assessment.church_code')`
- Each phase stores its data in `session('assessment.phaseN')` with `current_phase` pointer
- `assessment/index.blade.php` renders the correct phase container based on `$currentPhase`
- Footer NEXT button uses the HTML `form` attribute to target the active phase's form
- Puzzle pieces (bottom/left/right/top) hidden by default; shown via JS on phase completion
- Step counter in header dynamically highlights current/completed steps with `.currentStep` / `.completedStep` CSS

**Phase 1 — Personal Details** (`wizard-demographics.blade.php`):
- Fields: name, email, contact, gender, age, status, baptized, timeInFaith
- Validated via `StoreDemographicsRequest` Form Request
- Submits to `POST /assessment/phase1` → `storePhase1()`
- Saves `session('assessment.phase1')`, advances to phase 2

**Phase 2 — Skill Profiling** (`wizard-skills.blade.php`):
- 40 Likert questions (5/skill × 8 skills), grouped by skill
- Radio inputs 1-6: Strongly Agree(6) → Strongly Disagree(1)
- Submits to `POST /assessment/phase2` → `storePhase2()`
- Computes per-skill group totals, stores `session('assessment.phase2.scores')` + `groupTotals`
- Skill ID → name mapping: 1=Music, 2=Technology, 3=Writing, 4=Technical, 5=Speaking, 6=Accounting, 7=Mentoring, 8=Bible Knowledge

**Phase 3 — Interest & Passion** (`wizard-interest-and-passion.blade.php`):
- Questions grouped by ministry category (6 categories: Core, Support, Outreach, Creative & Media, Care & Healing, Special Interest)
- Same Likert 1-6 scale
- Submits to `POST /assessment/phase3` → `storePhase3()`
- Computes per-category group totals, stores `session('assessment.phase3')`

**Phase 4 — Behavioral** (`wizard-behavioral.blade.php`):
- Behavioral questions are **filtered** based on eligibility computation:
  1. Find top-scoring ministry categories from Phase 3 interest scores
  2. Filter ministries to those in top categories
  3. Filter by demographic restrictions (gender, age, marital, baptized, time in faith) against Phase 1 data
  4. Filter by skill restrictions (skill group total ≥ 10 = qualified) against Phase 2 data
  5. Only behavioral questions for remaining eligible ministries are displayed
- If no ministries pass all filters, a message is shown and NEXT advances to Phase 5
- Submits to `POST /assessment/phase4` → `storePhase4()`

**Session-based data flow:**
```
Church code → session('assessment.church_code')
Phase 1    → session('assessment.phase1')  = { name, email, contact, gender, age, status, baptized, timeInFaith, church_code }
Phase 2    → session('assessment.phase2')  = { scores: {qId: score, ...}, groupTotals: {skillId: total, ...} }
Phase 3    → session('assessment.phase3')  = { scores: {qId: score, ...}, groupTotals: {catId: total, ...} }
Phase 4    → session('assessment.phase4')  = { scores: {qId: score, ...} }
Phase 5    → TBD — store eligible_ministries, generate report, save to user_reports table
```

**Puzzle animation:**
- JS in `resources/js/assessment.js` handles puzzle piece display
- On page load: pieces for completed phases shown (`bottom` for Phase 1, `left` for Phase 2, etc.)
- On NEXT click: 3-second delay before form submission, with the current phase's puzzle piece animating in
- Piece mapping: Phase 1→bottom, Phase 2→left, Phase 3→right, Phase 4→top
- CSS transition: 1s ease-in-out scale + opacity, with a 1s purple drop-shadow highlight

**Step counter:**
- Dynamic CSS classes: `.currentStep` (purple, active) and `.completedStep` (green, ✓ checkmark)
- Steps update on each phase completion based on `$currentPhase`

**JS constraints respected:**
- No AJAX/Livewire for form submission
- No JS for form data handling
- JS only used for: puzzle animation timing, 3s NEXT delay, step counter is server-rendered
- HTML5 validation (`required` on radios) with `checkValidity()` + `reportValidity()` before delayed submit

### 3.3 Admin Layout & Sidebar (✅ Complete)

- ✅ **Admin layout** (`_layouts/admin.blade.php`) — full page wrapper with `@include('_partials.adminSide.sideNav')` + `@include('_partials.adminSide.topNav')` + mobile hamburger + overlay. Sidebar JS extracted to `resources/js/admin.js` (loaded via Vite). Logout modal moved here from sideNav for full-page coverage on mobile.
- ✅ **Sidebar partial** (`_partials/adminSide/sideNav.blade.php`) — replaces former `<x-admin-sidebar />` component. Fixed left sidebar, toggles between 250px (expanded) and 70px (icon-only).
  - Nav links: Dashboard, Restriction Editor, Question Editor, Settings (all using route names)
  - Logout button: clicking it also collapses the sidebar (`classList.remove('mobileOpen')`) + hides overlay before showing modal
  - Active link highlighted with purple left border (removed duplicate `inset` box-shadow)
  - Uses **Tabler icons** (`@tabler/icons-webfont`) instead of image files
  - Desktop: toggle chevron button. Mobile: hamburger + overlay.
- ✅ **Top nav partial** (`_partials/adminSide/topNav.blade.php`) — replaces former `<x-admin-top-nav />` component. Sticky top bar with page title. Hamburger visible on mobile.
- ✅ Removed `components/admin-sidebar.blade.php`, `components/admin-top-nav.blade.php`, `app/View/Components/AdminSidebar.php`, `app/View/Components/AdminTopNav.php` (switched to simple `@include` partials)
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
4. **Data endpoint** — `GET /admin/dashboard/data` with all filter params. Returns pre-mapped display values (gender→Male/Female, marital→Single/Married, etc.) — no conversion needed in JS.
5. **Ministry list** — Defined as `DashboardController::MINISTRIES` constant. Passed to view, rendered server-side via Blade `@foreach`. No JS population needed.

### 3.5 Admin Restrictions/Questions Editor (✅ Complete)

- **Contenteditable tables** — inline editing without input fields
- **Demographics tab:** Radio buttons for gender/marital/faith, number inputs for age range, toggle for baptized
- **Skills tab:** Switch toggles for Required/Not Required per skill
- **Questions tabs (3):** Skill Questions, Interest & Passion, Behavioral — each with EN + TL editable cells
- **Save/Reset buttons:** Save sends all data to server; Reset restores from admin ID 1 defaults with confirmation modal

### 3.6 Coding Convention Compliance (✅ Applied)

- **Blade components** — `@include('_partials.adminSide.sideNav')` → `<x-admin-sidebar />` and `<x-admin-top-nav />` (class-backed)
- **Form Requests** — Auth Form Requests in `app/Http/Requests/Auth/`: `LoginRequest`, `SendVerificationRequest`, `ChangePasswordRequest`. `RegisterRequest` and `CheckEmailRequest` removed (logic merged into `SendVerificationRequest` + `verifyRegistration`).
- **Action classes** — `app/Actions/CopyDefaults.php` extracted from `RegisterController`
- **Inline JS extracted** — Admin sidebar to `admin.js`, auth pages (login + register) to `auth.js`, dashboard to `admin-dashboard.js`. `admin-login.js` and `admin-register.js` merged into single `auth.js`.
- **Tabler Icons** — All inline SVGs replaced with `ti ti-*` classes from `@tabler/icons-webfont`
- **Password rules** — Min 8, uppercase, number, special (server-side only via Form Requests — removed from JS)

### 3.7 Frontend Assets

**Images in `public/images/` (current state):**
```
icn-logo.png, logo.png, banner.png, doveStatic.png,
footer.png, footer copy.png,
pzl-top.png, pzl-right.png, pzl-left.png, pzl-bottom.png    ← 11 files
```
- All icon images replaced with **Tabler icons** (`@tabler/icons-webfont`)
- Unused images removed: `bg.png`, `icn-closedEyes.png`, `icn-openEyes.png`, `icn-dashboard*`, `icn-restriction*`, `icn-question*`, `icn-settings*`, `icn-logout*`

**Still to copy from old project when needed:**
- `howItWorks.webp`, `icn-userCount.png`, `icn-submissionCount.png`, `icn-export*`, `icn-find.png`, `icn-grow.png`, `icn-honesty.png`, `el1.jpg`, `banner-cut.png`

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

// Admin auth — no middleware (guest access) (✅ 6 routes)
Route::prefix('admin')->group(function () {
    GET  /login                           Auth\LoginController@showLoginForm          admin.login
    POST /login                           Auth\LoginController@login
    GET  /register                        Auth\RegisterController@showRegisterForm    admin.register
    POST /send-verification               Auth\RegisterController@sendVerification     admin.send-verification
    POST /verify-registration             Auth\RegisterController@verifyRegistration   admin.verify-registration
    POST /forgot-password                 Auth\ForgotPasswordController@sendTempPassword  admin.forgot-password
    POST /validate-church-code            Auth\RegisterController@validateChurchCode
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

// Assessment — session-based (✅ 7 routes)
Route::prefix('assessment')->name('assessment.')->group(function () {
    POST /set-church-code   AssessmentController@setChurchCode   assessment.set-church-code
    GET  /                  AssessmentController@show            assessment.index
    POST /phase1            AssessmentController@storePhase1     assessment.phase1.store
    POST /phase2            AssessmentController@storePhase2     assessment.phase2.store
    POST /phase3            AssessmentController@storePhase3     assessment.phase3.store
    POST /phase4            AssessmentController@storePhase4     assessment.phase4.store
    GET  /reset             AssessmentController@reset           assessment.reset
});

// ❌ Still needed (Phase 5):
// api/user-reports, api/generate-profile
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
3. ✅ Auth controllers + views (separate login & register pages + forgot-password modal + verification modal)
4. ✅ Admin middleware + route registration
5. ✅ Email integration (Laravel Mail + Gmail SMTP + Mailable classes + email Blade views)
6. ✅ FrontendController + public routes (landing, ministries, privacy-policy)
7. ✅ Landing page modals (user type, church code, language, bible verse) — all buttons wired

### Phase 2: Core Assessment (✅ Phases 1-4 Complete)
8. ✅ AssessmentController — `setChurchCode()`, `show()`, `storePhase1-4()`, `reset()` (fixed behavioral question duplication: added `->where('user_id', $admin->id)` filter)
9. ✅ Ministry matching logic — `computeEligibleMinistries()` in AssessmentController (filters by interest category, demographics, skills)
10. ✅ Assessment wizard — All 4 phases built as server-side POST forms. Glass UI theme with purple `#8c52ff`, backdrop blur, animated gradient background. Likert 1-6 circular buttons, pill radios, progressive blur (answered=faded, future=blurred+locked), auto-scroll to next question. No category headers or question numbers. Puzzle animation.
11. ❌ Report creation + storage (Phase 5 — not yet built)

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
21. ✅ Form Request validation — LoginRequest, SendVerificationRequest, ChangePasswordRequest (RegisterRequest + CheckEmailRequest removed — merged into SendVerificationRequest)
22. ✅ Coding conventions applied — Blade components, Action classes, inline JS/CSS extracted, Tabler icons, naming conventions
23. ✅ Backend logic removed from JS — All validation, data mapping, and POST requests moved to controllers. JS is UI enhancement only (auth.js, admin.js, admin-dashboard.js, assessment.js)
24. ✅ Login & Register pages separated — dedicated Blade templates, unified auth.js, standard form POSTs, toast messaging
25. ❌ Session timeout handling
26. ❌ Responsive design testing

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
11. **Password strength rules:** Min 8 characters, at least 1 uppercase letter, 1 number, and 1 special character — enforced server-side only via `SendVerificationRequest` and `ChangePasswordRequest`. Client-side validation removed (JS only enhances UI).
12. **Vite entry points:** `admin.js` (sidebar toggle), `auth.js` (login + register pages — togglePassword, toast, forgot modal, auto-show verify), `admin-dashboard.js` (Chart.js dashboard), `assessment.js` (puzzle animation + NEXT button delay) — all registered in `vite.config.js`
13. **Assessment uses server-side session, not localStorage:** All phase data stored in `session('assessment.*')`. Church code set via `POST /assessment/set-church-code`, stored in session (not localStorage)
14. **No Livewire on assessment page:** Removed Livewire. All forms use standard HTML `method="POST"` with hidden `@csrf` inputs
15. **NEXT button uses HTML `form` attribute:** The footer button's `form="..."` attribute targets the active phase's form ID (`demographicForm`, `skillsForm`, `interestForm`, `behavioralForm`). JS intercepts click for 3s puzzle delay, then calls `form.submit()`
16. **Form submission bypasses HTML5 validation via JS:** When `form.submit()` is called programmatically, HTML5 `required` validation is skipped. Fixed by calling `form.checkValidity()` before the delay, and `form.reportValidity()` at submit time
17. **Phase 4 eligibility computation:** `computeEligibleMinistries()` evaluates all restrictions server-side: top interest categories → demographic filters → skill filters (group total ≥ 10 = qualified). If no ministries pass, a message is shown and submission advances to Phase 5
18. **Skill group threshold:** A skill group total ≥ 10 (out of max 30 = 5 questions × 6 points) qualifies the user for that skill. Matches old system
19. **Interest category ranking:** The top-scoring category/categories from Phase 3 determine which ministries are considered. Categories with the maximum total score (ties allowed) are kept
20. **Demographic restriction values:** `gender` 0=any, 1=male, 2=female; `marital_status` 0=any, 1=single, 2=married; `baptized` 1=required, 2=no restriction; `time_in_faith` 1=1+wk, 2=6+mo, 3=1+yr, 4=2+yr
21. **Reset clears entire assessment session:** `GET /assessment/reset` calls `session()->forget('assessment')` and redirects to landing page (`route('home')`). Church code must be re-entered

---

## 9. ✅ Refactoring: Admin JavaScript → Controllers

All backend logic has been moved from JS to Laravel controllers. JS is now used only for **enhancement/UX**.

### ✅ What was moved to controllers:
- **Form validation** — All validation in Form Requests (`SendVerificationRequest`, `ChangePasswordRequest`, `LoginRequest`). Password strength rules removed from JS.
- **Numeric field mappings** — `DashboardController::getData()` now returns mapped display values (Male/Female, Single/Married, etc.) instead of raw codes. `convertNumericFields()` removed from JS.
- **Static data** — Ministry list moved from JS array to `DashboardController::MINISTRIES` constant. Ministry checkboxes rendered server-side by Blade. `ministryList` removed from JS.
- **All form POSTs** — Sign-in, send-verification, verify-registration, forgot-password all use standard `<form method="POST">` submissions. JS has zero fetch calls. Controllers redirect with flash messages.
- **Password validation** — Removed from JS. Handled entirely by `SendVerificationRequest` (min 8, uppercase, number, special, confirmed).
- **Sign-up state** — `window.tempSignupData` removed. Password now stored in session by `sendVerification`, read by `verifyRegistration`.

### ✅ What JS kept doing:
- **`auth.js`** — togglePassword, toast notifications via CustomEvent, forgot password modal toggle, auto-show verify modal on `?verify=`
- **`admin.js`** — Sidebar toggle (desktop collapsed, mobile hamburger), mobile overlay close, admin@admin element hiding
- **`admin-dashboard.js`** — Chart.js rendering (7 charts), table row rendering from server-mapped data, filter param collection, API call to `/admin/dashboard/data`
- **`assessment.js`** — Puzzle animation timing, 3s NEXT delay, step counter is server-rendered