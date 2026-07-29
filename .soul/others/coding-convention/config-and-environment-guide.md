# Laravel Conventions — Config & Environment

**Laravel version reference:** 12.x / 13.x

---

## 1. Never Call `env()` Outside `config/*.php`

```php
// Correct — config/services.php
return [
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
];

// application code
config('services.stripe.key');
```

```php
// Wrong — env() called directly in application code
class PaymentController extends Controller
{
    public function charge()
    {
        $key = env('STRIPE_KEY'); // breaks once config is cached
    }
}
```

**Why:** `php artisan config:cache` (used in production) compiles all `config/*.php` files into one cached file and **stops reading `.env` at runtime entirely**. Any `env()` call outside a config file returns `null` after caching — a common production-only bug that doesn't appear in local dev.

---

## 2. Secrets Stay in `.env`, Never Hardcoded or Committed

```php
// Correct
'key' => env('STRIPE_KEY'),
```

```php
// Wrong — hardcoded secret in a committed file
'key' => 'sk_live_51H...',
```

**Rule:** Confirm `.env` is listed in `.gitignore` (it is by default in a fresh Laravel install — verify it hasn't been removed). Commit `.env.example` with placeholder keys instead, so the team knows what variables are required without exposing real values.

```
# .env.example
STRIPE_KEY=
STRIPE_SECRET=
```

---

## 3. Config Files Group by Service/Concern

```php
// config/services.php — third-party service credentials
return [
    'stripe' => [...],
    'mailgun' => [...],
];

// config/app.php — core app-level settings only
```

**Rule:** Don't add ad-hoc top-level keys to `config/app.php` for feature-specific settings — create or use a dedicated config file (`config/orders.php`, `config/billing.php`) so related settings stay grouped and discoverable.

```php
// config/orders.php
return [
    'max_quantity_per_order' => env('ORDERS_MAX_QUANTITY', 100),
    'auto_cancel_after_hours' => env('ORDERS_AUTO_CANCEL_HOURS', 48),
];
```

---

## 4. Environment-Specific Behavior via `config()`, Not `env()` Checks Scattered in Code

```php
// Correct
if (config('app.debug')) { ... }
if (app()->environment('local')) { ... }
```

```php
// Wrong
if (env('APP_ENV') === 'local') { ... } // same config-caching problem as above
```

---

## 5. Typed Config Access Where It Matters

For config values consumed in multiple places, consider a small typed accessor rather than repeating `config('...')` string paths with the risk of typos.

```php
// app/Support/OrderConfig.php
class OrderConfig
{
    public static function maxQuantity(): int
    {
        return config('orders.max_quantity_per_order');
    }
}
```
This is optional and scales with how many places consume the value — not required for a config key used once.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Reading env vars | Only inside `config/*.php`; application code uses `config()` |
| Secrets | `.env` only, never committed; `.env.example` documents required keys |
| Feature-specific settings | Dedicated `config/{feature}.php` file, not bolted onto `app.php` |
| Environment checks | `app()->environment()` / `config('app.debug')`, not raw `env()` in app code |