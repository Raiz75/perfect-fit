# Laravel Conventions — Console Commands

**Laravel version reference:** 12.x / 13.x

---

## 1. Command Naming/Signature Conventions

```php
// Correct — colon-namespaced, verb-first, matches the domain it acts on
class SyncProductInventory extends Command
{
    protected $signature = 'products:sync-inventory {--dry-run : Preview changes without writing}';
    protected $description = 'Sync product inventory counts from the supplier feed';
}

class SendWeeklyDigest extends Command
{
    protected $signature = 'reports:send-weekly-digest {--force : Send even if already sent this week}';
}
```

```php
// Wrong — unnamespaced, vague, inconsistent casing
class DoSync extends Command
{
    protected $signature = 'sync'; // collides easily, no domain context
}
```

**Rule:** Signature format is `{domain}:{action}` (`products:sync-inventory`, `reports:send-weekly-digest`), kebab-case for the action. Always set `$description` — it's what shows in `artisan list`. Use `{--option}` for flags that change behavior (`--dry-run`, `--force`) rather than positional arguments for anything optional.

---

## 2. Where Command Logic Lives — Thin Command, Logic in an Action

```php
// Correct — command is a thin CLI adapter around an Action class
class SyncProductInventory extends Command
{
    protected $signature = 'products:sync-inventory {--dry-run}';

    public function handle(SyncInventoryFromSupplierFeed $action): int
    {
        $result = $action->execute(dryRun: $this->option('dry-run'));

        $this->info("Synced {$result->count} products.");

        return self::SUCCESS;
    }
}
```

```php
// Wrong — business logic embedded directly in the command
class SyncProductInventory extends Command
{
    public function handle(): int
    {
        $feed = Http::get('https://supplier.example.com/feed')->json();
        foreach ($feed as $item) {
            Product::where('sku', $item['sku'])->update(['stock' => $item['stock']]);
        }
        // ...50 more lines of business logic, only reachable via CLI
        return self::SUCCESS;
    }
}
```

**Rule:** Same principle as controllers — a command's `handle()` method parses input, calls one Action/service, and formats output (`$this->info()`, `$this->table()`, exit codes). Business logic goes in an injectable Action class so it's reusable from a job, a controller, or a test without going through the console kernel. Return `self::SUCCESS` / `self::FAILURE` explicitly rather than relying on implicit `0`.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Signature format | `{domain}:{kebab-case-action}` |
| Optional behavior flags | `{--option}` |
| Description | Always set, shown in `artisan list` |
| Business logic | Lives in an injected Action/service, not the command |
| Exit codes | Explicit `self::SUCCESS` / `self::FAILURE` |