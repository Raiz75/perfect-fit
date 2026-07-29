# Laravel Conventions — Soft Deletes & Observers

**Laravel version reference:** 12.x / 13.x

---

## 1. When to Use `SoftDeletes` vs Hard Delete

```php
// Correct — SoftDeletes for records with audit/recovery value or referenced elsewhere
class Order extends Model
{
    use SoftDeletes; // orders are referenced by invoices, refunds, reports — never truly gone
}
```

```php
// Correct — hard delete for records with no downstream reference or retention need
class PasswordResetToken extends Model
{
    // no SoftDeletes — expired tokens should actually be removed, no audit value
}
```

**Rule:** Use `SoftDeletes` when: the record is referenced by other tables (foreign keys would otherwise dangle or cascade-delete history), there's a business/legal need to recover or audit "deleted" records, or undo functionality is a real product requirement. Use hard deletes when: the data has no downstream reference, retaining it has no value (expired tokens, temporary cache-like rows), or there's a legal requirement to actually erase it (e.g. GDPR erasure requests — note that soft-deleted rows must still be genuinely purged to comply, so don't rely on `SoftDeletes` alone as a compliance mechanism for erasure requests).

Remember: `SoftDeletes` automatically excludes trashed rows from default queries — anywhere the app needs to include or query only trashed records, it needs explicit `withTrashed()`/`onlyTrashed()`, and this is easy to forget in aggregation/reporting queries.

---

## 2. Model Observers vs `booted()` Hooks vs Events — When Each Fits

```php
// Correct — Observer for a cohesive set of lifecycle hooks on one model, kept in a separate class
class OrderObserver
{
    public function creating(Order $order): void
    {
        $order->uuid ??= Str::uuid();
    }

    public function deleted(Order $order): void
    {
        Log::info('Order soft-deleted', ['order_id' => $order->id]);
    }
}

// AppServiceProvider::boot() or OrderServiceProvider::boot()
Order::observe(OrderObserver::class);
```

```php
// Correct — booted() hook for a single, trivial, always-true rule intrinsic to the model itself
class Order extends Model
{
    protected static function booted(): void
    {
        static::creating(fn (Order $order) => $order->uuid ??= Str::uuid());
    }
}
```

```php
// Correct — Event (from earlier guide) for side effects that are optional, multiple, or need to be decoupled/queued
class Order extends Model
{
    protected $dispatchesEvents = [
        'created' => OrderPlaced::class,
    ];
}
// OrderPlaced has independent listeners: SendConfirmationEmail, NotifyWarehouse, UpdateLoyaltyPoints
```

**Rule:**
- **`booted()` hook**: for a single, simple, always-applicable rule that's intrinsic to the model's own integrity (auto-generating a UUID, normalizing a field on save). Keep it to one or two lines — anything more belongs in an Observer.
- **Observer**: for a related *set* of lifecycle hooks on one model (multiple `creating`/`updating`/`deleted` behaviors) that would otherwise clutter `booted()`, or when the logic needs its own dependencies via constructor injection (Observers are resolved from the container).
- **Event + Listeners**: for side effects that are optional, numerous, need to run on a queue, or belong logically to other domains (notifying a different bounded context) rather than to the model's own integrity. Prefer events over Observers when the reacting code doesn't "belong" to the model itself — e.g. sending an email isn't the Order's responsibility, it's a listener's.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Recoverable/audited/referenced records | `SoftDeletes` |
| No downstream value, or legal erasure requirement | Hard delete |
| Excluding/including trashed rows in reports | Explicit `withTrashed()`/`onlyTrashed()` — don't assume default scoping |
| Single trivial self-contained rule | `booted()` hook |
| Multiple related lifecycle hooks, or DI needed | Observer |
| Optional/multiple/queueable/cross-domain side effects | Event + Listeners |