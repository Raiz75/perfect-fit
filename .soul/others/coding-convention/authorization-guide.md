# Laravel Conventions — Authorization

**Laravel version reference:** 12.x / 13.x

---

## 1. Policies vs Gates — When to Use Which

```php
// Correct — model-centric authorization → Policy
class OrderPolicy
{
    public function update(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }
}
```

```php
// Correct — non-model, app-wide rule → Gate
// AppServiceProvider::boot()
Gate::define('access-admin-panel', fn (User $user) => $user->is_admin);
```

**Rule:**
- Use a **Policy** whenever authorization is tied to a specific Eloquent model (`view`, `update`, `delete`, etc. on `Order`, `Post`, `Comment`).
- Use a **Gate** for authorization that isn't tied to a single model instance — feature flags, admin panel access, cross-model rules (e.g. "can this user export reports").
- Don't define a Gate for something that's really a policy method with an implicit model — that's a sign the check should move to a Policy.

---

## 2. Policy Method Naming

```php
// Correct — matches Laravel's authorization-ability conventions
class PostPolicy
{
    public function viewAny(User $user): bool {}
    public function view(User $user, Post $post): bool {}
    public function create(User $user): bool {}
    public function update(User $user, Post $post): bool {}
    public function delete(User $user, Post $post): bool {}
    public function restore(User $user, Post $post): bool {}
    public function forceDelete(User $user, Post $post): bool {}
}
```

```php
// Wrong — non-standard names break resource-controller auto-resolution
class PostPolicy
{
    public function canEdit(User $user, Post $post): bool {}
    public function isOwner(User $user, Post $post): bool {}
}
```

**Rule:** Stick to the standard set (`viewAny`, `view`, `create`, `update`, `delete`, `restore`, `forceDelete`). `authorizeResource()` on a resource controller maps HTTP actions to these names automatically — deviating from them forces manual `authorize()` calls everywhere and breaks that convention for no benefit.

---

## 3. `authorize()` in Controllers vs Form Requests vs Middleware (`can:`)

```php
// Correct — single action in a standard controller
public function update(Request $request, Order $order)
{
    $this->authorize('update', $order);
    // ...
}
```

```php
// Correct — authorization co-located with validation for a dedicated Form Request
class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('order'));
    }
}
```

```php
// Correct — route-level, no controller logic needed to gate access
Route::put('/orders/{order}', [OrderController::class, 'update'])
    ->middleware('can:update,order');
```

**Rule:**
- **Form Request `authorize()`**: default choice when the endpoint already has a Form Request for validation — keeps the "can this user do this" check next to "is this input valid."
- **`can:` middleware**: use for simple, single-ability route gates where there's no Form Request and the check needs to run before any controller code executes (e.g. protecting an entire route group).
- **`$this->authorize()` in the controller**: use when the check depends on logic resolved inside the action (e.g. after loading a related model) or when there's no Form Request in play.
- Don't duplicate the same check across two of these layers for the same route — pick one.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Model-tied checks | Policy |
| Non-model / app-wide checks | Gate |
| Policy method names | `viewAny`, `view`, `create`, `update`, `delete`, `restore`, `forceDelete` |
| Validated endpoint w/ Form Request | `authorize()` inside the Form Request |
| Simple route-level gate | `can:` middleware |
| Check depends on in-action logic | `$this->authorize()` in controller |