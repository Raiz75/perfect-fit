---
type: integrations
services:
  - name: DeepSeek AI
    purpose: Generate AI-based ministry assessment interpretations for user reports
    sdk: none (custom HTTP client via Laravel's Http facade)
    webhook_route: null
last_updated: 2026-07-28
---

# External Integrations

## DeepSeek AI (`App\Services\DeepSeekService`)
- **Purpose**: Generates natural-language interpretations of assessment results, stored in `user_reports.ai_interpretation`.
- **Implementation**: Custom service class using Laravel's `Http` facade. API key and model name configured via `config/services.php` (reads from `.env`: `DEEPSEEK_API_KEY`, `DEEPSEEK_MODEL`).
- **Binding**: Registered as a singleton in `AppServiceProvider::register()`.
- **Webhook routes**: None. This is a request/response API call, not a webhook integration.

## Email (Laravel Mail)
- **Mailer**: `log` in development (configurable via `MAIL_MAILER` in `.env`).
- **Drivers**: Postmark, Resend, SES are configured in `config/services.php` but not currently used.
- **Queue**: Required for email delivery — database queue with `php artisan queue:listen`.

## References
- [Laravel Webhooks — How to handle](https://laravel.com/docs/routing#csrf-protection)
- [Laravel HTTP Client](https://laravel.com/docs/http-client)
- [Laravel Notifications — Third-party channels](https://laravel.com/docs/notifications#driver-prerequisites)
