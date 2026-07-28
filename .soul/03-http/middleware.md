---
type: middleware-summary
path: app/Http/Middleware
custom_middleware:
  - name: AdminMiddleware
    applied_to: [/admin/* routes]
global_middleware: [Laravel defaults]
last_updated: 2026-07-28
---

# Middleware

## AdminMiddleware (`App\Http\Middleware\AdminMiddleware`)
Checks if the user is authenticated. Redirects to `admin.login` for guests; returns 401 JSON for API/XMLHttpRequest requests. Registered as the `admin` alias in `bootstrap/app.php:14`. Applied to all `/admin/*` routes (dashboard, restrictions, questions, settings, logout).

No other custom middleware exists.

## References
- [Laravel Middleware](https://laravel.com/docs/middleware)
- [Laravel Middleware Groups](https://laravel.com/docs/middleware#middleware-groups)
- [Laravel Rate Limiting via Middleware](https://laravel.com/docs/routing#rate-limiting)
