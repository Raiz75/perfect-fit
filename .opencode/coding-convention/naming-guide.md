# Laravel Conventions — Naming

**Laravel version reference:** 12.x / 13.x

---

## 1. Models
Singular, PascalCase.

```php
// Correct
class User extends Model {}
class OrderItem extends Model {}

// Wrong
class Users extends Model {}
class order_item extends Model {}
```
**Why:** Laravel's Eloquent resolver assumes singular model → plural snake_case table by default (`User` → `users`). Breaking this forces you to manually declare `protected $table` everywhere, which defeats convention-over-configuration.

---

## 2. Tables
Plural, snake_case.

```
users
order_items
password_reset_tokens
```

**Rule:** If a model name is a compound word, snake_case the whole thing consistently — `OrderItem` → `order_items`, not `orderitems` or `order-items`.

---

## 3. Controllers
Singular resource name + `Controller` suffix.

```php
// Correct
class PostController extends Controller {}
class UserProfileController extends Controller {}

// Wrong
class PostsController extends Controller {}
class UserProfile extends Controller {}
```

**Rule:** Resource controllers generated via `php artisan make:controller PostController --resource` already follow this — don't rename after generation.

---

## 4. Migrations
Let Artisan generate the timestamp prefix. Name describes the action, not just the table.

```bash
php artisan make:migration create_orders_table
php artisan make:migration add_status_to_orders_table
php artisan make:migration add_index_to_orders_email
```

**Rule:** Never hand-edit the timestamp prefix on a migration filename — it determines execution order and must stay chronological relative to when it was created.

---

## 5. Foreign Keys
`{singular_related_model}_id`

```php
// orders table
user_id       // references users.id
product_id    // references products.id

// Wrong
userId
uid
user_fk
```

**Rule:** This convention lets `belongsTo()` relationships resolve without explicitly passing a foreign key argument.

---

## 6. Pivot Tables
Singular model names, alphabetical order, joined by underscore.

```
role_user      // Role <-> User
product_tag    // Product <-> Tag
```

**Wrong:**
```
user_roles
tags_products
```

**Rule:** Alphabetical order matters for Eloquent's `belongsToMany()` to resolve the pivot table name automatically without passing it explicitly.

---

## 7. Routes

**URIs:** kebab-case
```
/password-reset
/order-history
```

**Route names:** dot notation
```php
Route::get('/password-reset', ...)->name('password.reset');
Route::get('/orders/{order}/invoice', ...)->name('orders.invoice');
```

**Rule:** Dot notation groups related routes for readability and works cleanly with `route()` helper namespacing (`orders.invoice` vs `ordersInvoice`).

---

## 8. Config Keys
snake_case, nested by service/concern.

```php
// config/services.php
return [
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
];

// usage
config('services.stripe.key');
```

---

## Quick Reference Table

| Element | Case | Example |
|---|---|---|
| Model | PascalCase, singular | `OrderItem` |
| Table | snake_case, plural | `order_items` |
| Controller | PascalCase + `Controller` | `OrderItemController` |
| Foreign key | snake_case + `_id` | `order_id` |
| Pivot table | snake_case, alphabetical, singular | `role_user` |
| Route URI | kebab-case | `/order-items` |
| Route name | dot notation | `order-items.show` |
| Config key | snake_case | `services.stripe.key` |