# Laravel Conventions — Migrations

**Laravel version reference:** 12.x / 13.x

---

## 1. One Logical Change Per Migration

```bash
php artisan make:migration create_orders_table
php artisan make:migration add_status_to_orders_table
php artisan make:migration add_shipped_at_to_orders_table
```

```php
// Wrong — bundling unrelated changes into one migration "for convenience"
Schema::table('orders', function (Blueprint $table) {
    $table->string('status')->default('pending');
    $table->timestamp('shipped_at')->nullable();
});
Schema::table('users', function (Blueprint $table) {
    $table->string('phone')->nullable(); // unrelated to orders — separate migration
});
```

**Why:** isolated migrations are easier to review, roll back individually, and reason about in a diff.

---

## 2. Never Edit a Migration That's Already Run in Shared/Production

```bash
# Wrong — editing orders_table migration after it's deployed
# Correct — write a new migration to alter the existing table
php artisan make:migration add_discount_to_orders_table
```

```php
public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->decimal('discount', 8, 2)->default(0)->after('total');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('discount');
    });
}
```

**Rule:** Editing an already-run migration desyncs environments — anyone who already ran the old version won't get the change, and `migrate:fresh` on a new environment will produce a different schema history than production.

---

## 3. Foreign Keys via Shorthand

```php
// Correct
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('product_id')->constrained();
    $table->timestamps();
});
```

```php
// Wrong — verbose manual equivalent, error-prone
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
```

---

## 4. Always Write a Working `down()` Method

```php
public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('tracking_number')->nullable();
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('tracking_number');
    });
}
```

**Rule:** Even if rollback is rarely used, an incomplete/missing `down()` breaks `migrate:rollback` for the whole team when it's actually needed (e.g., undoing a bad deploy).

---

## 5. Indexes for Columns Used in `WHERE`/`ORDER BY`/Foreign Keys

```php
Schema::table('orders', function (Blueprint $table) {
    $table->index('status');
    $table->index(['user_id', 'created_at']); // composite, for common query patterns
});
```

**Rule:** `foreignId()->constrained()` already indexes the FK column automatically — don't add a duplicate manual index on top of it. Add explicit indexes for columns frequently filtered/sorted on that aren't already covered.

---

## 6. Seeders/Factories Stay in Sync With Schema

Whenever a migration adds a required (non-nullable, no-default) column, update the corresponding factory in the same PR.

```php
// database/factories/OrderFactory.php
public function definition(): array
{
    return [
        'user_id' => User::factory(),
        'status' => 'pending', // keep in sync with new required columns
    ];
}
```

---

## Quick Reference

| Concern | Convention |
|---|---|
| Scope per migration | One logical schema change |
| Already-deployed migrations | Never edit — write a new migration |
| Foreign keys | `foreignId()->constrained()` shorthand |
| Rollback | Always implement a correct `down()` |
| Indexing | Explicit indexes on frequently filtered/sorted columns not already covered by FK constraints |
| Factories | Updated alongside required-column migrations |