# Laravel Conventions — Routing

**Laravel version reference:** 12.x / 13.x

---

## 1. Group Related Routes

```php
// Correct
Route::prefix('admin')->name('admin.')->middleware(['auth', 'can:admin'])->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
});
```

```php
// Wrong — repeated prefix/middleware on every line
Route::get('/admin/users', [AdminUserController::class, 'index'])->middleware(['auth', 'can:admin'])->name('admin.users.index');
Route::get('/admin/orders', [AdminOrderController::class, 'index'])->middleware(['auth', 'can:admin'])->name('admin.orders.index');
```

---

## 2. Route Model Binding

```php
// Correct
Route::get('/orders/{order}', [OrderController::class, 'show']);

class OrderController extends Controller
{
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }
}
```

```php
// Wrong — manual lookup duplicated across every method that needs the model
Route::get('/orders/{id}', [OrderController::class, 'show']);

class OrderController extends Controller
{
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('orders.show', compact('order'));
    }
}
```

**Rule:** Use scoped bindings for nested resources to avoid leaking cross-tenant/cross-user data:
```php
Route::get('/users/{user}/orders/{order}', ...)->scopeBindings();
```
This ensures `$order` must actually belong to `$user`, not just exist.

---

## 3. Separate Concerns by File

```
routes/
├── web.php      (session-based, Blade/Livewire/Inertia pages)
├── api.php       (stateless, token-based)
├── console.php   (Artisan closures)
```

**Rule:** Don't define API logic in `web.php` or vice versa — middleware defaults differ (`web` group has CSRF/session, `api` group doesn't), and mixing them creates subtle bugs (missing CSRF protection, or an API route unexpectedly requiring a CSRF token).

---

## 4. Resource Routes Over Manual CRUD Routes

```php
// Correct
Route::resource('orders', OrderController::class);
```

```php
// Wrong — manually reproducing what resource() already generates
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/create', [OrderController::class, 'create']);
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{order}', [OrderController::class, 'show']);
Route::get('/orders/{order}/edit', [OrderController::class, 'edit']);
Route::put('/orders/{order}', [OrderController::class, 'update']);
Route::delete('/orders/{order}', [OrderController::class, 'destroy']);
```

**When to break this:** if you only need 2-3 of the 7 RESTful actions, use `Route::resource(...)->only(['index', 'show'])` rather than defining every method just to satisfy the resource convention.

---

## 5. Named Routes, Always

```php
// Correct
Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');

// usage
route('orders.invoice', $order);
```

```php
// Wrong — hardcoded URL strings scattered through views/redirects
return redirect('/orders/' . $order->id . '/invoice');
```

**Why:** hardcoded paths break silently when a URI changes; `route()` calls don't.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Repeated prefix/middleware | `Route::group()` / `Route::prefix()`/`name()` chains |
| Fetching a model from a route param | Route model binding, not manual `find()`/`findOrFail()` |
| Nested resource ownership | `->scopeBindings()` |
| CRUD routes | `Route::resource()`, scoped with `->only()`/`->except()` as needed |
| Linking/redirecting | `route('name')`, never hardcoded URI strings |
| API vs web logic | Kept in separate route files |