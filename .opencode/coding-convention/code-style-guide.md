# Laravel Conventions — Code Style

**Laravel version reference:** 12.x / 13.x (PHP 8.2+/8.3+)

---

## 1. Laravel Pint for Formatting

```bash
./vendor/bin/pint          # fix formatting
./vendor/bin/pint --test   # check without modifying (CI)
```

**Rule:** Don't hand-debate spacing/brace-placement/PSR-12 details in code review — let Pint enforce it automatically. Add it as a CI step so unformatted code fails the pipeline rather than relying on manual review.

`pint.json` (optional, to customize ruleset):
```json
{
    "preset": "laravel"
}
```

---

## 2. Type-Hint Everything Feasible

```php
// Correct
class CreateOrder
{
    public function handle(array $data, User $user): Order
    {
        // ...
    }
}

class Order extends Model
{
    protected function total(): Attribute
    {
        return Attribute::make(
            get: fn (int $value): string => number_format($value / 100, 2),
        );
    }
}
```

```php
// Wrong — no return type, no param types, relies on docblocks or nothing at all
class CreateOrder
{
    public function handle($data, $user)
    {
        // ...
    }
}
```

**Rule:** Type declarations catch bugs at the boundary (wrong type passed in) before they surface as runtime errors deep in the call stack, and they make IDE autocomplete/static analysis actually useful.

---

## 3. Constructor Promotion for DTOs/Services

```php
// Correct
class CreateOrderData
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity,
        public readonly ?string $couponCode = null,
    ) {}
}
```

```php
// Wrong — verbose, error-prone, boilerplate-heavy
class CreateOrderData
{
    public $productId;
    public $quantity;
    public $couponCode;

    public function __construct($productId, $quantity, $couponCode = null)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->couponCode = $couponCode;
    }
}
```

---

## 4. Named Arguments Over Long Unclear Positional Lists

```php
// Correct — clear at the call site
$order = new CreateOrderData(
    productId: $product->id,
    quantity: 2,
    couponCode: $request->coupon,
);
```

```php
// Wrong — unclear what each positional value means without checking the constructor
$order = new CreateOrderData($product->id, 2, $request->coupon);
```

**Rule:** Use named arguments once a constructor/method has 3+ parameters, especially when several share the same type (e.g., multiple strings/ints) where positional order is easy to get wrong silently.

---

## 5. Laravel 13 PHP Attributes — Use Consistently, Don't Mix Randomly

Laravel 13 introduced attribute-based configuration as an alternative to property-based config (models, controllers, jobs, commands, listeners, mailables).

```php
// Attribute style (Laravel 13+)
#[Table('orders')]
class Order extends Model {}
```

```php
// Property style (still fully supported)
class Order extends Model
{
    protected $table = 'orders';
}
```

**Rule:** Both are valid — pick one style per codebase/team and apply it consistently. Don't mix attribute-style and property-style for the same kind of configuration across different models in the same project; it makes the codebase harder to scan.

---

## 6. Avoid Deeply Nested Conditionals — Prefer Early Returns

```php
// Correct
public function handle(Order $order): void
{
    if (! $order->isPending()) {
        return;
    }

    if (! $order->hasStock()) {
        throw new OutOfStockException($order);
    }

    $order->markAsProcessing();
}
```

```php
// Wrong — nested, harder to follow
public function handle(Order $order): void
{
    if ($order->isPending()) {
        if ($order->hasStock()) {
            $order->markAsProcessing();
        } else {
            throw new OutOfStockException($order);
        }
    }
}
```

---

## Quick Reference

| Concern | Convention |
|---|---|
| Formatting | Laravel Pint, enforced in CI, not manual review |
| Type hints | On all params/returns/properties where PHP allows it |
| Simple data carriers | Constructor promotion + `readonly` |
| Multi-param calls | Named arguments once 3+ params |
| Laravel 13 attributes vs properties | Pick one style, apply consistently |
| Nested conditionals | Early returns over deep nesting |