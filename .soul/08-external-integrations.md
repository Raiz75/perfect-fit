---
type: integrations
services:
  - name: DeepSeek AI
    purpose: Generate AI-based ministry assessment interpretations for user reports
    sdk: none (custom HTTP client via Laravel's Http facade)
    webhook_route: null
last_updated: 2026-07-30
---

# External Integrations

## DeepSeek AI (`App\Services\DeepSeekService`)
- **Purpose**: Generates natural-language interpretations of assessment results, stored in `user_reports.ai_interpretation`.
- **Implementation**: Custom service class using Laravel's `Http` facade. API key and model name configured via `config/services.php` (reads from `.env`: `DEEPSEEK_API_KEY`, `DEEPSEEK_MODEL`).
- **Binding**: Registered as a singleton in `AppServiceProvider::register()`.
- **Webhook routes**: None. This is a request/response API call, not a webhook integration.
- **History**: Originally planned to use OpenAI (gpt-4o-mini) with `openai-php/laravel` package. Migrated to DeepSeek mid-project to avoid the OpenAI dependency and move AI logic server-side (old `callApi.js` had the API key client-side, a security concern).

## Dompdf (`barryvdh/laravel-dompdf`)
- **Purpose**: Server-side PDF generation for admin dashboard reports.
- **Implementation**: Laravel wrapper for Dompdf (PHP HTML → PDF converter). `ReportController::generate()` loads a Blade view, renders it to PDF via `Pdf::loadView()`, and returns a download response.
- **Page numbering**: Uses Dompdf `setCallbacks()` API with `end_document` event → `Canvas::text()` called per page via `processPageScript()`.
- **Config**: A4 portrait, 15mm margins (default). `isPhpEnabled` not needed — callbacks registered via API.

## pChart/CpChart (`szymach/c-pchart`)
- **Purpose**: Server-side chart image generation for PDF reports.
- **Implementation**: Pure PHP charting library (no JS/browser required). Wrapped in `App\Services\ChartImageService`.
- **Charts**: 7 chart types rendered as PNG images: pie (gender, age), doughnut (baptized), bar (faith, skills, ministry, marital).
- **Storage**: Temporary PNGs in `storage/app/private/report-charts/`, cleaned up after PDF download via `ChartImageService::cleanup()`.
- **Palette**: Colors match frontend `admin-dashboard.js` Chart.js colors exactly (hardcoded in `ReportController::getChartPalettes()`).
- **Mailer**: `log` in development (configurable via `MAIL_MAILER` in `.env`).
- **Drivers**: Postmark, Resend, SES are configured in `config/services.php` but not currently used.
- **Queue**: Required for email delivery — database queue with `php artisan queue:listen`.

## References
- [Laravel Webhooks — How to handle](https://laravel.com/docs/routing#csrf-protection)
- [Laravel HTTP Client](https://laravel.com/docs/http-client)
- [Laravel Notifications — Third-party channels](https://laravel.com/docs/notifications#driver-prerequisites)
