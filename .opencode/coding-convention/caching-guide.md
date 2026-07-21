# Laravel Conventions — Caching

**Laravel version reference:** 12.x / 13.x

---

## 1. Cache Key Naming/Namespacing

```php
// Correct — namespaced, predictable, includes identifying params
Cache::remember("orders.user.{$user->id}.recent", now()->addMinutes(10), function () use ($user) {
    return $user->orders()->latest()->limit(5)->get();
});

Cache::remember("product.{$product->id}.price", now()->addHour(), fn () => $product->calculatePrice());
```

```php
// Wrong — ambiguous, collides across unrelated features
Cache::remember('recent', now()->addMinutes(10), fn () => $user->orders()->latest()->get());
Cache::remember($user->id, now()->addHour(), fn () => $product->calculatePrice()); // key collides with other per-user caches
```

**Rule:** Use a dot-namespaced pattern: `{domain}.{entity}.{id}.{aspect}` (e.g. `orders.user.42.recent`, `product.17.price`). Never use a bare ID or generic word as a key — it's guaranteed to collide with an unrelated cache entry somewhere else in the app. Centralize key-building in a static method or dedicated class if the same key is constructed from more than one place.

```php
class OrderCacheKeys
{
    public static function recentForUser(int $userId): string
    {
        return "orders.user.{$userId}.recent";
    }
}
```

---

## 2. Tagged Cache Invalidation Strategy

```php
// Correct — tagged so related entries can be invalidated together (requires Redis/Memcached driver)
Cache::tags(['orders', "user:{$user->id}"])->remember(
    "orders.user.{$user->id}.recent",
    now()->addMinutes(10),
    fn () => $user->orders()->latest()->get()
);

// On order creation/update:
Cache::tags("user:{$user->id}")->flush();
```

```php
// Wrong — manually tracking and deleting individual keys on every mutation
Cache::forget("orders.user.{$user->id}.recent");
Cache::forget("orders.user.{$user->id}.count");
Cache::forget("orders.user.{$user->id}.total"); // easy to miss one and serve stale data
```

**Rule:** When the cache driver supports tags (Redis, Memcached — not `file`/`database`), tag entries by the entities that can invalidate them and flush by tag rather than tracking individual keys manually. If the driver doesn't support tags, keep TTLs short enough that staleness is acceptable, or invalidate via explicit `Cache::forget()` calls co-located with the mutation that causes staleness (e.g. in a model observer).

---

## 3. What Belongs in Cache vs What Shouldn't

```php
// Correct — expensive to compute, safe to share/short-lived, non-sensitive
Cache::remember('homepage.featured_products', now()->addHour(), fn () => Product::featured()->get());
```

```php
// Wrong — per-user sensitive data cached without scoping or with too long a TTL
Cache::remember('user_profile', now()->addDay(), fn () => $user->load('paymentMethods', 'addresses'));
// bare key with no user ID — will leak between users if key isn't uniquely scoped
```

**Rule:**
- Cache: expensive aggregations/queries, data shared across many requests, computed values with a clear invalidation trigger.
- Never cache: session-bound authorization decisions, unscoped per-user sensitive data (payment details, tokens) without a uniquely-scoped key and a deliberately short TTL, or anything where staleness could cause an authorization bypass (e.g. caching "is this user still active" for too long).
- If a cached value contains sensitive data, the key **must** include the user/tenant ID, and the TTL should be as short as the use case tolerates — don't cache sensitive data "because it's slow to query" without also solving the underlying query performance.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Key format | `{domain}.{entity}.{id}.{aspect}`, centralized key-builder if reused |
| Invalidation (Redis/Memcached) | Tagged cache, flush by tag |
| Invalidation (file/database driver) | Short TTLs or explicit `forget()` at the mutation site |
| Safe to cache | Expensive, shared, non-sensitive, clear invalidation path |
| Avoid caching | Unscoped sensitive data, authorization-critical state, long TTL on per-user data |