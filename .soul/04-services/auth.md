---
type: auth-service
driver: session
guards: [web]
token_expiration: null
providers: [users]
mfa_enabled: false
password_reset_flow: custom
last_updated: 2026-07-28
---

# Authentication

## Flow
Login is email+password via session-based auth (`Auth\LoginController`). Registration requires email verification (6-digit code sent via `VerificationCodeMail`) and generates a unique 9-character church code. Password reset uses a temporary password sent via `TemporaryPasswordMail`. Logout invalidates the session.

The `admin` middleware alias (registered in `bootstrap/app.php`) protects all `/admin/*` dashboard routes. Registration copies all default restrictions and questions from admin user ID=1 via `App\Actions\CopyDefaults`.

## Guards & Providers
Single guard: `web` (session driver, eloquent provider using `App\Models\User`).

## References
- [Laravel Authentication](https://laravel.com/docs/authentication)
- [Laravel Authorization](https://laravel.com/docs/authorization)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Laravel Passport](https://laravel.com/docs/passport)
- [Laravel Fortify](https://laravel.com/docs/fortify)
- [Laravel Jetstream](https://jetstream.laravel.com/docs)
- [Laravel Password Reset](https://laravel.com/docs/authentication#password-reset)
