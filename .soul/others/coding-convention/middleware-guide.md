# Laravel Conventions — Middleware

**Laravel version reference:** 12.x / 13.x

---

## 1. Naming and Registration (`bootstrap/app.php` in 11+)

```php
// Correct — descriptive name, registered with an alias in bootstrap/app.php
class EnsureSubscriptionIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()->subscription?->active, 403, 'Subscription required.');
        return $next($request);
    }
}

// bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'subscribed' => EnsureSubscriptionIsActive::class,
    ]);
})
```

```php
// Wrong — vague class name, no alias, referenced by FQCN everywhere
class CheckStuff { /* ... */ }
Route::middleware([\App\Http\Middleware\CheckStuff::class])->group(...);
```

**Rule:** Name middleware as an imperative check (`EnsureX`, `RedirectIfY`, `CheckZ`) describing exactly what it verifies or does. Register route-usable middleware with a short alias in `bootstrap/app.php` (`->withMiddleware()`) rather than referencing the fully-qualified class name at every route definition — this is the 11+/12+ convention replacing the old `app/Http/Kernel.php` `$routeMiddleware` array.

---

## 2. Route-Specific vs Global Middleware

```php
// Correct — global middleware only for things that apply to every single request
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(TrustProxies::class); // applies to all requests, no exceptions
})

// Route-specific — applied only where relevant
Route::middleware(['auth', 'subscribed'])->group(function () {
    Route::get('/billing', [BillingController::class, 'index']);
});
```

```php
// Wrong — auth or feature-specific middleware registered globally
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(EnsureSubscriptionIsActive::class); // now runs on login, register, public pages...
})
```

**Rule:** Global middleware is reserved for concerns that genuinely apply to every request regardless of route (trusted proxies, CORS, request logging, maintenance mode). Anything auth-related, feature-gated, or role-specific belongs on route groups or individual routes via alias. If adding a global middleware requires exceptions/exclusions for specific routes, that's a sign it should be route-specific instead.

---

## 3. Where Request-Mutation Logic Belongs (Middleware vs Controller)

```php
// Correct — request normalization that must happen before routing/validation, in middleware
class TrimAndConvertEmptyStringsToNull
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->merge(array_map(fn ($v) => $v === '' ? null : (is_string($v) ? trim($v) : $v), $request->all()));
        return $next($request);
    }
}
```

```php
// Wrong — request-shape mutation duplicated inside every controller action
public function store(Request $request)
{
    $data = $request->all();
    $data = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $data);
    // ...same code repeated in update(), and in every other controller
}
```

**Rule:** Middleware is for cross-cutting request transformation that applies uniformly across many routes (trimming input, normalizing headers, setting locale from a header/subdomain, attaching a request ID). Controller-specific data shaping — deriving a value used only by that one action, casting a single field for one form — stays in the controller or, better, in a Form Request's `prepareForValidation()`. If the same mutation is copy-pasted across multiple controllers, that's the signal to promote it to middleware.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Naming | `EnsureX` / `RedirectIfY` / `CheckZ`, registered as alias in `bootstrap/app.php` |
| Applies to every request | Global middleware |
| Applies to specific routes/roles/features | Route-group or per-route middleware |
| Cross-cutting request normalization | Middleware |
| Action-specific data shaping | Controller or Form Request `prepareForValidation()` |