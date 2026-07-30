---
type: architecture-overview
laravel_version: 12.x
entry_points: [public/index.php]
service_providers:
  - AppServiceProvider
boot_sequence_notes: false
last_updated: 2026-07-30
---

# Architecture Overview

## Request Lifecycle
`public/index.php` → Laravel boot (configured via `bootstrap/app.php`) → global middleware → route matching → controller action (or Blade view for non-admin pages).

Public pages (home, ministries, privacy policy) and the assessment flow (4 phases, session-only) are accessible without authentication. Admin routes (`/admin/*`) are protected by the `admin` middleware alias registered in `bootstrap/app.php`, which checks for an authenticated session.

## Service Container Bindings
- **AppServiceProvider::register()** binds `DeepSeekService` as a singleton, injecting `HttpClient`, the DeepSeek API key from `config('services.deepseek.key')`, and the model from `config('services.deepseek.model')`.
- **ChartImageService** is instantiated directly in `ReportController` via dependency injection (no binding needed).

## PDF Report Flow
`POST /admin/dashboard/report` → `ReportController::generate()` → `GenerateReportRequest` (validates filter params) → query `UserReport` with same filters as `DashboardController::getData()` → aggregate chart data (7 sections) → `ChartImageService` renders chart images via pChart → Dompdf renders Blade template to PDF → download response. Page numbering via Dompdf `end_document` callback + Canvas `text()`.

## Custom Providers
- **AppServiceProvider** — registers the `DeepSeekService` singleton; `boot()` is empty.

## References
- [Laravel Request Lifecycle](https://laravel.com/docs/lifecycle)
- [Laravel Service Container](https://laravel.com/docs/container)
- [Laravel Service Providers](https://laravel.com/docs/providers)
- [Laravel Facades](https://laravel.com/docs/facades)
