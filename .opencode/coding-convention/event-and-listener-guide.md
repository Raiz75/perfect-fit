# Laravel Conventions — Events & Listeners

**Laravel version reference:** 12.x / 13.x

---

## 1. When to Use Events vs Calling Logic Directly

```php
// Correct — multiple, unrelated side effects triggered by one action
class PlaceOrder
{
    public function handle(Order $order): void
    {
        $order->markAsPlaced();

        event(new OrderPlaced($order));
    }
}

// Listeners: SendOrderConfirmationEmail, NotifyWarehouse, UpdateLoyaltyPoints
```

```php
// Wrong — a single, tightly-coupled side effect wrapped in an event for no reason
event(new PasswordFieldValidated($password)); // one listener, always runs, adds indirection with no benefit
```

**Rule:** Use events when an action has multiple independent side effects, when those side effects may grow over time, or when decoupling the trigger from the reaction is genuinely valuable (e.g. third-party packages hooking in). If there's exactly one listener that always runs and always will, call the logic directly — an event is unnecessary indirection.

---

## 2. Naming — `OrderPlaced` vs `OrderWasPlaced`

```php
// Correct — past tense, no "Was"
class OrderPlaced {}
class UserRegistered {}
class InvoicePaid {}
```

```php
// Wrong — inconsistent tense/voice
class OrderWasPlaced {}
class PlacingOrder {}
class OrderPlacedEvent {} // redundant "Event" suffix
```

**Rule:** Name events in simple past tense describing what already happened (`OrderPlaced`, not `OrderWasPlaced` or `PlaceOrder`). Don't suffix with `Event` — the namespace (`App\Events`) already conveys that. Keep this consistent across the whole codebase; don't mix `Was`-prefixed and plain past-tense names.

---

## 3. Queued Listeners vs Sync

```php
// Correct — implements ShouldQueue for anything with I/O (email, external API, slow work)
class SendOrderConfirmationEmail implements ShouldQueue
{
    public function handle(OrderPlaced $event): void
    {
        Mail::to($event->order->user)->send(new OrderConfirmation($event->order));
    }
}
```

```php
// Correct — sync listener for fast, in-process work only
class UpdateOrderSearchIndex
{
    public function handle(OrderPlaced $event): void
    {
        $event->order->searchable(); // fast, no external I/O
    }
}
```

**Rule:** Any listener performing I/O (email, HTTP calls, file writes, slow queries) implements `ShouldQueue`. Keep the request/response cycle unblocked. Sync listeners are reserved for fast, in-memory work with no external dependency. Never make a listener sync "temporarily" for debugging and leave it that way in production.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Single, fixed side effect | Call directly, no event |
| Multiple/independent side effects | Event + listeners |
| Event naming | Past tense, no `Was`, no `Event` suffix |
| I/O-bound listeners | `implements ShouldQueue` |
| Fast, in-process listeners | Sync |