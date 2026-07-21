# Laravel Conventions — Eloquent

**Laravel version reference:** 12.x / 13.x

---

## 1. Always Declare Mass-Assignment Protection

```php
// Correct
class User extends Model
{
    protected $fillable = ['name', 'email', 'password'];
}
```

```php
// Wrong — wide open, any request field can write any column
class User extends Model
{
    protected $guarded = [];
}
```

**Rule:** `$guarded = []` disables mass-assignment protection entirely. Only use it in narrow, deliberate cases (e.g., an internal seeder/factory context) — never on a model backing a public-facing form.

Laravel 13 also supports the `#[Fillable]` attribute as an alternative to the property — functionally equivalent, pick one style and stay consistent within a codebase.

---

## 2. Use Relationship Methods, Not Raw Joins

```php
// Correct
class Order extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

$order->user;
$order->items;
```

```php
// Wrong — bypasses Eloquent's relationship layer without a proven reason
DB::table('orders')
    ->join('users', 'orders.user_id', '=', 'users.id')
    ->select('orders.*', 'users.name')
    ->get();
```

**Exception:** Raw joins/query builder are fine for reporting queries, heavy aggregation, or measured performance-critical paths — not as a default style choice.

---

## 3. Eager Load to Avoid N+1

```php
// Correct
$orders = Order::with('user', 'items.product')->get();
```

```php
// Wrong — triggers a query per order for `user`
$orders = Order::all();
foreach ($orders as $order) {
    echo $order->user->name; // N+1
}
```

**Enforce it in dev/local:**
```php
// AppServiceProvider::boot()
Model::preventLazyLoading(! app()->isProduction());
```
This throws in local/CI when lazy loading occurs, catching N+1s before they reach production.

---

## 4. Query Scopes for Reusable Conditions

```php
// Correct
class Order extends Model
{
    public function scopePending(Builder $query): void
    {
        $query->where('status', 'pending');
    }

    public function scopeForUser(Builder $query, User $user): void
    {
        $query->where('user_id', $user->id);
    }
}

Order::pending()->forUser($user)->get();
```

```php
// Wrong — repeated raw conditions scattered across the codebase
Order::where('status', 'pending')->get(); // in controller A
Order::where('status', 'pending')->get(); // in controller B, job C, etc.
```

**Rule:** Any `where()` condition used in 2+ places belongs in a scope.

---

## 5. Casts for Dates, JSON, Enums

```php
// Correct — Laravel 11+ casts() method (or $casts property, either is fine)
class Order extends Model
{
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'shipped_at' => 'datetime',
            'status' => OrderStatus::class, // enum
        ];
    }
}

$order->shipped_at->diffForHumans(); // Carbon instance, no manual parsing
$order->metadata['tracking_number'];  // decoded automatically
```

```php
// Wrong — manual decode/parse scattered in controllers
$metadata = json_decode($order->metadata, true);
$shippedAt = Carbon::parse($order->shipped_at);
```

---

## 6. Enums for Fixed Value Sets

```php
enum OrderStatus: string
{
    case Pending = 'pending';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
}
```

**Rule:** Any column with a fixed, known set of string values (status, type, role) should be backed by a PHP enum + cast — not raw string comparisons (`if ($order->status === 'shipped')`) scattered through the app.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Mass assignment | Explicit `$fillable`, never blanket `$guarded = []` on public-facing models |
| Relationships | Eloquent relationship methods over raw joins, unless performance-justified |
| N+1 prevention | `with()` eager loading + `preventLazyLoading()` in non-prod |
| Reusable conditions | Query scopes, not repeated `where()` calls |
| Dates/JSON/fixed sets | `casts()`/`$casts`, backed by enums where applicable |