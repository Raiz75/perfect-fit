# Laravel Conventions — Testing

**Laravel version reference:** 12.x / 13.x (Pest or PHPUnit — examples below use Pest syntax with PHPUnit equivalents noted)

---

## 1. Feature Tests vs Unit Tests

- **Feature tests** (`tests/Feature/`) — HTTP-level behavior: routes, validation, auth, full request/response cycle.
- **Unit tests** (`tests/Unit/`) — isolated logic: a single class/method with no framework bootstrapping needed (Services, Actions, helpers, value objects).

```php
// tests/Feature/OrderTest.php — feature test
it('creates an order for an authenticated user', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 25]);

    $this->actingAs($user)
        ->post('/orders', ['product_id' => $product->id, 'quantity' => 2])
        ->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'total' => 50,
    ]);
});
```

```php
// tests/Unit/DiscountCalculatorTest.php — unit test, no DB/HTTP involved
it('applies a 10% discount over $100', function () {
    $calculator = new DiscountCalculator();

    expect($calculator->apply(150))->toBe(135.0);
});
```

**Rule:** Don't put pure-logic tests in `Feature/` just because it's the default folder — misclassifying tests slows the suite down unnecessarily (feature tests boot the full framework).

---

## 2. Factories Over Hand-Built Arrays

```php
// Correct
$user = User::factory()->create();
$order = Order::factory()->for($user)->create();
```

```php
// Wrong — brittle, breaks silently when schema changes
$user = User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    // if a new required column is added, every hand-built array like this breaks
]);
```

**Rule:** Factories centralize default attribute generation — a schema change only requires updating the factory, not every test file.

---

## 3. `RefreshDatabase` for DB-Touching Tests

```php
// Pest: tests/Pest.php or per-file
uses(RefreshDatabase::class);

// PHPUnit
class OrderTest extends TestCase
{
    use RefreshDatabase;
}
```

**Rule:** Don't share mutable DB state across tests without this trait — tests should be independent and re-runnable in any order.

---

## 4. Test Actions and Outcomes, Not Implementation Details

```php
// Correct — asserts on observable outcome
it('sends a confirmation email when an order is placed', function () {
    Mail::fake();

    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)->post('/orders', ['product_id' => $product->id, 'quantity' => 1]);

    Mail::assertSent(OrderConfirmation::class);
});
```

```php
// Wrong — over-specified, breaks on harmless refactors
it('calls the CreateOrder action exactly once with specific internal args', function () {
    // asserting internal method call counts/args couples the test
    // to implementation rather than behavior
});
```

---

## 5. Naming and Organization

```php
// Correct — descriptive, behavior-first
it('rejects an order with a quantity of zero', function () { ... });
it('returns a 403 when a non-owner tries to update an order', function () { ... });

// Wrong — vague
it('test order', function () { ... });
it('works', function () { ... });
```

**Rule:** A failing test name alone should tell you what broke, without opening the file.

---

## 6. Mirror the App Structure in Test Directories

```
tests/
├── Feature/
│   ├── Http/
│   │   └── OrderControllerTest.php
│   └── Auth/
│       └── LoginTest.php
├── Unit/
│   ├── Actions/
│   │   └── CreateOrderTest.php
│   └── Services/
│       └── DiscountCalculatorTest.php
```

---

## Quick Reference

| Concern | Convention |
|---|---|
| HTTP/route/auth behavior | `tests/Feature/` |
| Isolated class/method logic | `tests/Unit/` |
| Test data | Factories, not hand-built arrays |
| DB state | `RefreshDatabase` trait |
| Assertions | On outcomes/behavior, not internal implementation details |
| Test names | Descriptive enough to diagnose failure from the name alone |