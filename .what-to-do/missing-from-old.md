# Missing Features — Old Project → New Project

Comparison between `Perfit/perfit-old/perfit` (plain PHP) and `Perfit/perfit-new/perfit` (Laravel 12).

---

## Pages

| Old (perfit-old) | New (perfit-new) | Status |
|---|---|---|
| `index.html` — Landing page (hero, how-it-works, ministry carousel, bible verse, user type/church code/language modals) | `Index.blade.php` — Hero + How It Works only | **Partial** — missing modals, carousel, bible verse |
| `admin.html` — Admin login/signup with email verification + forgot password | — | **Missing** |
| `adminPanel.html` — Dashboard (6 charts, report table with filters, restrictions editor, questions editor, settings, PDF export) | — | **Missing** |
| `assessment.html` — 5-phase wizard (personal details → 40 skill Qs → 30 interest Qs → behavioral Qs → AI results) | — | **Missing** |
| `ministry.html` — 23 ministries info with carousels + requirement tables | — | **Missing** |
| `privacyPolicy.html` — Static privacy policy | — | **Missing** |

---

## Backend / PHP Endpoints

| Endpoint | Method | Purpose | Status |
|---|---|---|---|
| `php-dbCon.php` | — | DB connection wrapper | **Missing** — use Laravel Eloquent instead |
| `php-signIn.php` | POST | Admin login, start session | **Missing** |
| `php-signUp.php` | POST | Admin registration (copies defaults from admin #1) | **Missing** |
| `php-checkEmail.php` | POST | Check if admin email exists | **Missing** |
| `php-sendVerification.php` | POST | Send 6-digit code via PHPMailer | **Missing** |
| `php-forgotPass.php` | POST | Send temporary password via email | **Missing** |
| `php-validateChurchCode.php` | POST | Validate church code (case-sensitive) | **Missing** |
| `php-getDbData.php` | POST | Get all ministries, restrictions, questions by churchCode | **Missing** |
| `php-createUserReport.php` | POST | Save volunteer assessment report | **Missing** |
| `php-sessionTest.php` | GET | Check if admin session is active | **Missing** — use Laravel auth |
| `php-adminFilter.php` | GET | Get filtered user reports + counts | **Missing** |
| `php-loadAdminPanel.php` | GET | Load all admin panel data | **Missing** |
| `php-saveRestriction.php` | POST | Save restrictions/questions edits | **Missing** |
| `php-resetRestriction.php` | POST | Reset restrictions to defaults | **Missing** |
| `php-changeChurchName.php` | POST | Update church name | **Missing** |
| `php-updatePass.php` | POST | Change admin password | **Missing** |
| `php-logout.php` | POST | Destroy admin session | **Missing** — use Laravel auth |

---

## Database Tables

| Table | Purpose | Status |
|---|---|---|
| `admin` | Admin accounts (email, password, churchName, churchCode) | **Missing** |
| `ministries` | 27 ministries | **Missing** |
| `ministry_category` | 6 ministry categories for interest profiling | **Missing** |
| `restrictions_demographic` | Per-ministry demographic filters (gender, age, marital, baptized, timeInFaith) | **Missing** |
| `restrictions_skill` | Per-ministry skill requirements (8 skills toggle) | **Missing** |
| `skills` | 8 skills (Music, Technology, Writing, Technical, Speaking, Accounting, Mentoring, BibleKnowledge) | **Missing** |
| `questions_skill` | 40 skill profiling questions (5 per skill, EN + TL) | **Missing** |
| `questions_interest_and_passion` | 30 interest questions (5 per category, EN + TL) | **Missing** |
| `questions_behavioral` | Behavioral questions per ministry (EN + TL) | **Missing** |
| `user_report` | Volunteer assessment results | **Missing** |

Current new project only has default Laravel tables: `users`, `password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`.

---

## Features

| Feature | Old | New | Details |
|---|---|---|---|
| **Landing Page Hero** | ✅ | ✅ | Banner + PERFIT title + CTA |
| **Landing Page Modals** (user type, church code, language) | ✅ | ❌ | 3 overlay modals with localStorage |
| **Ministry Carousel** (23 ministries, infinite loop) | ✅ | ❌ | Vanilla JS carousel with clone technique |
| **Random Bible Verse** (dove animation + overlay) | ✅ | ❌ | 28 hardcoded verses |
| **How It Works Section** | ✅ | ✅ | 6-step timeline |
| **5-Phase Assessment** | ✅ | ❌ | Wizard with localStorage persistence |
| — Phase 1: Personal Details form | ✅ | ❌ | Name, email, contact, gender, age, status, baptized, faith duration |
| — Phase 2: Skill Profiling (40 Qs, Likert scale) | ✅ | ❌ | 8 skills × 5 questions |
| — Phase 3: Interest & Passion (30 Qs) + rule-based filtering | ✅ | ❌ | 6 categories × 5 questions; filters by demographics + skills |
| — Phase 4: Behavioral Profiling (variable Qs) | ✅ | ❌ | Per filtered ministry |
| — Phase 5: AI Results (OpenAI GPT-4o-mini) | ✅ | ❌ | Pie chart + AI-generated profile + PDF export |
| **Session Recovery** (detect incomplete assessment) | ✅ | ❌ | Offers to continue previous session |
| **Puzzle Completion Animation** | ✅ | ❌ | Puzzle pieces assemble per phase |
| **Bilingual UI** (English + Tagalog) | ✅ | ❌ | `uiTranslation` JS object |
| **Admin Auth** (login/signup/forgot/logout) | ✅ | ❌ | Standard email + password |
| **Admin Dashboard** | ✅ | ❌ | Report table with 6 Chart.js charts |
| — Filters (search, date range, demographics, skills, ministries) | ✅ | ❌ | |
| — 6 Charts (gender, age, faith duration, skills, ministry eligibility, baptized, marital) | ✅ | ❌ | Chart.js + plugin-datalabels |
| — PDF Report Export | ✅ | ❌ | jsPDF + jspdf-autotable |
| **Restrictions Editor** (demographic + skill per ministry) | ✅ | ❌ | Contenteditable tables + radio toggles |
| **Questions Editor** (skill / interest / behavioral) | ✅ | ❌ | Contenteditable tables (EN + TL) |
| **Admin Settings** (church name, code, change password) | ✅ | ❌ | |
| **Ministries Info Page** (23 ministries with carousels + requirements) | ✅ | ❌ | |
| **Privacy Policy** | ✅ | ❌ | |
| **Email Notifications** (verification code, forgot password) | ✅ | ❌ | PHPMailer |
| **CSRF Protection** | ❌ | ❌ | Old had none; new should use Laravel's |
| **API Key Security** (OpenAI) | ❌ (client-side) | ❌ | Old exposed key in browser; new should use server-side |

---

## Frontend Assets

| Asset | Old | New |
|---|---|---|
| CSS Framework | None (custom inline CSS) | Tailwind CSS v4 |
| CSS Approach | Desktop-first with media queries | Utility classes + CDN |
| JS Framework | Vanilla JS | Vanilla JS + Axios |
| JS Libraries (CDN) | Chart.js, chartjs-plugin-datalabels, jsPDF, jspdf-autotable | None |
| State Management | localStorage | None yet |
| Build Tool | None | Vite 7 |
| Images | Logo, banner, bg, footer, puzzle pieces, icons (20+ files) | Logo, banner, bg only (3 files) |

---

## Architecture

| Aspect | Old | New |
|---|---|---|
| Backend | Plain PHP (procedural, MySQLi) | Laravel 12 (MVC, Eloquent) |
| Routing | Direct file includes | Laravel Router |
| Auth | PHP sessions (custom) | Laravel Auth (customizable) |
| ORM | Raw MySQLi prepared statements | Eloquent ORM |
| Email | PHPMailer library | Laravel Mail + any driver |
| API Calls | Fetch API to PHP endpoints | Axios + Laravel routes |
| OpenAI Integration | Client-side (API key exposed) | Server-side (secure) |
