---
type: index
vault_version: 1.0
last_updated: 2026-07-28
covers:
  - app/
  - routes/
  - database/
  - tests/
---

# INDEX

## Architecture
- [[01-architecture/overview]] — Request lifecycle, DeepSeekService singleton, AppServiceProvider
- [[01-architecture/tech-stack]] — PHP 8.2, Laravel 12, SQLite, database-driven cache/queue/session, Livewire 4
- [[01-architecture/deployment]] — Local env only, no Docker/CI-CD, queue worker not configured

## Domain
- [[02-domain/relationships]] — 14 relationships across 9 models, no polymorphic relations
- [[02-domain/database-schema]] — 18 tables (12 app + 6 framework), frozen seeder IDs
- Models:
  - [[02-domain/models/user]] — Church admin with unique church_code, template user ID=1
  - [[02-domain/models/ministry]] — 29 church ministries grouped by category
  - [[02-domain/models/ministry-category]] — 6 high-level ministry groups
  - [[02-domain/models/skill]] — 8 skill areas (Music, Technology, etc.)
  - [[02-domain/models/demographic-restriction]] — Demographic eligibility per ministry
  - [[02-domain/models/skill-restriction]] — Required skills per ministry (8 booleans)
  - [[02-domain/models/skill-question]] — Phase 2 self-assessment questions (bilingual)
  - [[02-domain/models/interest-and-passion-question]] — Phase 3 interest questions (bilingual)
  - [[02-domain/models/behavioral-question]] — Phase 4 behavioral questions (bilingual)
  - [[02-domain/models/user-report]] — Submitted assessment results with AI interpretation

## HTTP
- [[03-http/routes]] — ~40 web routes, no API routes, admin routes protected by `admin` middleware
- [[03-http/middleware]] — Single custom middleware: AdminMiddleware (alias `admin`)
- [[03-http/requests]] — 4 form requests (Login, SendVerification, ChangePassword, StoreDemographics)
- [[03-http/resources]] — No API resources defined
- Controllers:
  - [[03-http/controllers/frontend-controller]] — Public pages (home, ministries, privacy)
  - [[03-http/controllers/assessment-controller]] — 4-phase assessment flow (session-based)
  - [[03-http/controllers/login-controller]] — Admin login (email + password)
  - [[03-http/controllers/register-controller]] — Admin registration with email verification + church code
  - [[03-http/controllers/logout-controller]] — Session invalidation
  - [[03-http/controllers/forgot-password-controller]] — Temporary password by email
  - [[03-http/controllers/dashboard-controller]] — Admin dashboard with Chart.js stats
  - [[03-http/controllers/question-controller]] — Manage assessment questions (skill/interest/behavioral)
  - [[03-http/controllers/restriction-controller]] — Manage ministry restrictions (demographic/skill)
  - [[03-http/controllers/settings-controller]] — Church name and password settings

## Services
- [[04-services/auth]] — Session-based auth, custom verification code + temp password flow
- [[04-services/notifications]] — No Notification classes; 2 Mailables (VerificationCodeMail, TemporaryPasswordMail)
- [[04-services/jobs-and-queues]] — No jobs defined, database queue used for email
- [[04-services/events-listeners]] — No events or listeners defined

## Authorization
- [[05-policies-and-permissions]] — No Policies, Gates, or roles/permissions package

## Conventions
- [[06-conventions/coding-standards]] — Pint (PSR-12), no strict_types, no static analysis
- [[06-conventions/naming]] — PascalCase models, Controller/Request suffixes, camelCase test methods
- [[06-conventions/testing-strategy]] — PHPUnit, Feature+Unit, SQLite in-memory, no coverage enforcement

## Decisions
- [[07-decisions/ADR-001-session-only-assessment]] — Session-only assessment flow (no API, no Livewire)
- [[07-decisions/ADR-002-pinned-seeder-ids]] — Frozen IDs for ministries (1-29), categories (1-6), skills (1-8)
- [[07-decisions/ADR-003-deepseek-ai-integration]] — DeepSeek AI for assessment interpretation
- [[07-decisions/ADR-004-user-id-1-template-pattern]] — User ID 1 as default template (multi-tenant-like pattern)
- [[07-decisions/ADR-005-blade-vanilla-js-over-framework]] — Blade + vanilla JS for assessment over Livewire/Inertia

## Reference
- [[08-external-integrations]] — DeepSeek AI API (custom HTTP client)
- [[09-glossary]] — 18 domain terms including field value mappings and thresholds
- [[99-known-issues]] — Phase 5 not built, missing queue supervisor, no domain factories, OpenAI→DeepSeek migration
