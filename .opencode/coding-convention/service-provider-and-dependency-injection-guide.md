# Laravel Conventions — Service Providers & Dependency Injection

**Laravel version reference:** 12.x / 13.x

---

## 1. What Belongs in a Custom Service Provider vs `AppServiceProvider`

```php
// Correct — dedicated provider for a cohesive, self-contained concern
class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);
    }

    public function boot(): void
    {
        Event::listen(ChargeSucceeded::class, RecordPaymentInLedger::class);
    }
}
```

```php
// Wrong — every unrelated binding/boot hook dumped into AppServiceProvider
class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // payment bindings, search bindings, third-party SDK config,
        // model observers, view composers — all mixed together, 200+ lines
    }
}
```

**Rule:** `AppServiceProvider` is for genuinely app-wide, low-volume concerns (global model settings like `Model::preventLazyLoading()`, a couple of small bindings). Once a concern has more than a few related bindings/boot hooks (payments, search, a third-party SDK integration, a bounded domain module), give it its own Service Provider named after the concern and register it in `bootstrap/providers.php`. This keeps `AppServiceProvider` from becoming a dumping ground and makes it possible to disable/swap a whole concern by removing one provider.

---

## 2. Binding Interfaces to Implementations in the Container

```php
// Correct — bind the interface, type-hint the interface everywhere else
interface PaymentGateway
{
    public function charge(Order $order): Charge;
}

class StripePaymentGateway implements PaymentGateway { /* ... */ }

// PaymentServiceProvider::register()
$this->app->bind(PaymentGateway::class, StripePaymentGateway::class);

class CheckoutController
{
    public function __construct(private PaymentGateway $gateway) {}
}
```

```php
// Wrong — controller depends on the concrete implementation directly
class CheckoutController
{
    public function __construct(private StripePaymentGateway $gateway) {}
    // swapping providers now means editing every consumer
}
```

**Rule:** Bind an interface to a concrete implementation whenever there's a real possibility of swapping implementations (different payment gateways per environment, a mock for testing, a future provider change) or when the concrete class has external dependencies you want to isolate. Don't introduce an interface for a class with exactly one implementation and no foreseeable need to swap it — that's premature abstraction. Use `singleton()` instead of `bind()` when the implementation is stateless or expensive to construct and should be shared for the request lifecycle.

---

## 3. Constructor Injection vs Facades — When Each Is Appropriate

```php
// Correct — constructor injection in classes resolved by the container (controllers, jobs, listeners)
class CheckoutController
{
    public function __construct(private PaymentGateway $gateway, private OrderRepository $orders) {}
}
```

```php
// Correct — facades for framework services in contexts where DI is awkward (routes/closures, quick scripts, static helper contexts)
Route::get('/health', fn () => response()->json(['cache' => Cache::has('warm-check')]));
```

```php
// Wrong — facade used inside a class that's otherwise fully constructor-injected, for no reason
class CheckoutController
{
    public function __construct(private OrderRepository $orders) {}

    public function store(Request $request)
    {
        Log::info('checkout started'); // fine
        $gateway = app(PaymentGateway::class); // should have been constructor-injected like everything else
    }
}
```

**Rule:** Prefer constructor injection for any class the container resolves for you (controllers, jobs, listeners, commands, form requests) — it makes dependencies explicit and testable via mocking. Facades (`Cache::`, `Log::`, `Event::`) are acceptable for framework-level services, especially in closures/routes where constructor injection isn't applicable, and for genuinely stateless utility calls (`Log::info()`) even inside injected classes. Don't mix `app()`/facade resolution with constructor injection for the *same* dependency type within one class — pick one pattern per class.

---

## Quick Reference

| Concern | Convention |
|---|---|
| App-wide, low-volume config | `AppServiceProvider` |
| Cohesive multi-binding concern | Dedicated Service Provider |
| Multiple/swappable implementations | Bind interface → implementation |
| Single implementation, no foreseeable swap | No interface — bind/inject concrete class |
| Container-resolved classes | Constructor injection |
| Framework services in closures/routes, stateless utility calls | Facades |