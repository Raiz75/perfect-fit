# Laravel Conventions — Jobs & Queues

**Laravel version reference:** 12.x / 13.x

---

## 1. Job Naming

```php
// Correct — imperative verb phrase describing the action performed
class SendOrderConfirmationEmail implements ShouldQueue {}
class GenerateMonthlyInvoice implements ShouldQueue {}
class SyncProductInventory implements ShouldQueue {}
```

```php
// Wrong — vague or noun-only names that don't describe the action
class OrderJob implements ShouldQueue {}
class InvoiceProcessing implements ShouldQueue {}
```

**Rule:** Name jobs as imperative verb phrases (`VerbNoun`), same convention as single-action classes/Actions. The name alone should tell you what the job does without opening the file.

---

## 2. `$tries` / `$backoff` / `$timeout` Conventions

```php
// Correct — explicit, deliberate values per job based on its failure characteristics
class ChargeCustomerCard implements ShouldQueue
{
    public $tries = 3;
    public $backoff = [10, 30, 60]; // seconds between each retry
    public $timeout = 30;

    public function failed(\Throwable $exception): void
    {
        Log::error('Card charge failed permanently', ['exception' => $exception]);
    }
}
```

```php
// Wrong — relying on framework defaults for a job with real failure/cost implications
class ChargeCustomerCard implements ShouldQueue
{
    // no $tries, no $backoff, no failed() — retries indefinitely per queue worker config
}
```

**Rule:**
- Every job that calls an external service (payment gateway, third-party API, email provider) sets `$tries` and `$backoff` explicitly — don't rely on the queue worker's global `--tries` flag.
- Use backoff arrays (`[10, 30, 60]`) for jobs prone to transient failures (rate limits, temporary network issues) rather than a flat retry delay.
- Set `$timeout` below the queue worker's `retry_after` config value to avoid duplicate execution of a still-running job.
- Always implement `failed()` for jobs with side effects that matter (payments, external state changes) so permanent failures are visible, not silent.

---

## 3. Idempotency in Job Handling

```php
// Correct — job is safe to run more than once
class ChargeCustomerCard implements ShouldQueue
{
    public function handle(): void
    {
        if ($this->order->fresh()->is_charged) {
            return; // already processed — safe no-op
        }

        $charge = $this->paymentGateway->charge($this->order, idempotencyKey: $this->order->uuid);
        $this->order->update(['is_charged' => true, 'charge_id' => $charge->id]);
    }
}
```

```php
// Wrong — re-running this job double-charges the customer
class ChargeCustomerCard implements ShouldQueue
{
    public function handle(): void
    {
        $this->paymentGateway->charge($this->order);
        $this->order->update(['is_charged' => true]);
    }
}
```

**Rule:** Every queued job must tolerate being executed more than once — queues guarantee at-least-once delivery, not exactly-once. Check current state before performing a non-idempotent side effect, and pass idempotency keys to external APIs that support them (payment gateways, most webhook-triggering services).

---

## 4. Queue Naming/Prioritization Per Job Type

```php
// Correct — job routed to a queue matching its priority/resource profile
class SendOrderConfirmationEmail implements ShouldQueue
{
    public function __construct()
    {
        $this->onQueue('emails');
    }
}

class GenerateMonthlyInvoice implements ShouldQueue
{
    public function __construct()
    {
        $this->onQueue('reports'); // long-running, lower priority
    }
}

class ChargeCustomerCard implements ShouldQueue
{
    public function __construct()
    {
        $this->onQueue('payments'); // high priority, isolated from bulk work
    }
}
```

**Rule:** Don't run every job on the `default` queue. Separate queues by resource profile and priority — time-sensitive/user-facing (`payments`, `notifications`) from bulk/background (`reports`, `imports`) — and run dedicated workers per queue so a backlog of slow jobs doesn't delay urgent ones. Name queues after the *domain of work*, not the job class.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Naming | Imperative verb phrase, describes the action |
| External-service jobs | Explicit `$tries`, `$backoff`, `$timeout`, `failed()` |
| Execution guarantee | Design for at-least-once — check state, use idempotency keys |
| Queue assignment | `onQueue()` per domain/priority, not all on `default` |