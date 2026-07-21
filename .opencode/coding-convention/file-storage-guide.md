# Laravel Conventions — File Storage

**Laravel version reference:** 12.x / 13.x

---

## 1. Disk Configuration Conventions (`public`, `s3`, `local`)

```php
// Correct — disk chosen based on access requirements, configured in config/filesystems.php
Storage::disk('public')->put('avatars/'.$filename, $contents);   // publicly accessible via symlinked storage
Storage::disk('local')->put('exports/'.$filename, $contents);    // private, app-only access
Storage::disk('s3')->put('uploads/'.$filename, $contents);       // production, durable, CDN-backed
```

```php
// Wrong — defaulting to 'local' for user-facing public assets, or 'public' for sensitive files
Storage::disk('local')->put('avatars/'.$filename, $contents); // avatar now has no public URL path
Storage::disk('public')->put('tax-documents/'.$filename, $contents); // sensitive file world-readable via symlink
```

**Rule:**
- `public`: user-facing assets that need a direct public URL (avatars, product images) — requires `php artisan storage:link`.
- `local`: private files the app serves through a controlled, authorized route (never a direct public URL) — invoices, exports, admin-only uploads.
- `s3` (or equivalent object storage): production default for anything user-uploaded, especially at scale — durable, offloads storage from app servers, works across horizontally-scaled instances (`local` disk doesn't persist/share across multiple app servers).
- Never store genuinely sensitive documents (ID scans, tax forms, contracts) on `public` — always serve them through an authorized controller action that checks a Policy before streaming the file from a private disk.

---

## 2. File Naming/Path Structure for Uploads

```php
// Correct — random/hashed filename, original name preserved as metadata, organized by entity
$path = $file->store("users/{$user->id}/avatars", 'public');
// → users/42/avatars/a1b2c3d4e5f6.jpg

$upload = Upload::create([
    'path' => $path,
    'original_name' => $file->getClientOriginalName(),
    'mime_type' => $file->getMimeType(),
]);
```

```php
// Wrong — trusting the client-supplied filename directly
$file->move(public_path('uploads'), $file->getClientOriginalName());
// path traversal risk, filename collisions, and encodes no ownership/entity relationship
```

**Rule:** Never persist a file using the client-supplied filename directly — use `store()`/`storeAs()` with a generated name (Laravel's default `store()` behavior already hashes the filename) and keep the original name in a database column if it needs to be shown to users (e.g. on download). Organize paths by owning entity (`{entity}/{id}/{category}/...`) so storage structure mirrors the data model and per-entity cleanup (e.g. deleting all of a user's files) is a simple prefix operation. Always validate MIME type and extension server-side — never trust the client-reported `Content-Type`.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Public assets | `public` disk + `storage:link` |
| Private/authorized-access files | `local` disk, served via controller + Policy |
| Production user uploads | `s3` (or equivalent object storage) |
| Filenames | Generated/hashed, never client-supplied directly |
| Path structure | `{entity}/{id}/{category}/...` |
| Upload validation | Server-side MIME/extension check, never trust client `Content-Type` |