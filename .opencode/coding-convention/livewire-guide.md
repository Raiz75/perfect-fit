# Livewire v4 — Coding Conventions

**Package version:** `livewire/livewire:^4.0` (released Jan 2026, current ~4.3.x)

---

## 1. Component Format — Choose by Complexity

```php
// Correct — simple component, single-file (SFC)
php artisan make:livewire counter
```
```
resources/views/components/⚡counter.blade.php
```

```php
// Correct — complex component (multi-method, dedicated CSS/JS, own test), multi-file (MFC)
php artisan make:livewire order-wizard --mfc
```
```
⚡order-wizard/
├── order-wizard.php
├── order-wizard.blade.php
├── order-wizard.css
├── order-wizard.js
└── order-wizard.test.php
```

```php
// Wrong — jamming a large, multi-concern component into a single 300-line SFC file
// no separation of CSS/JS/tests, hard to review or navigate
```
**Why:** SFC keeps small components easy to scan in one file; MFC keeps large components organized without forcing everything into one unwieldy block.

---

## 2. Naming

```php
// Correct
public string $email = '';         // typed, camelCase
public bool $isEditing = false;

public function save() {}          // verb-first action
public function deleteItem($id) {}

#[Computed]
public function totalPrice(): float {}   // noun-first computed
```

```php
// Wrong
public $Email;                     // untyped, PascalCase
public $editing_flag;              // snake_case, unclear name

public function process() {}       // vague verb, unclear what it does
public function render() { }       // colliding with a reserved Livewire method name if misused elsewhere
```

---

## 3. Routing

```php
// Correct — v4 named-component syntax for page components
Route::livewire('/posts/create', 'pages::post.create');
```

```php
// Wrong — mixing v3 class-reference syntax into new v4 code without reason
Route::get('/posts/create', CreatePost::class); // fine only for unmigrated legacy components
```

```php
// Wrong — registering a full page component outside the pages:: namespace
Route::livewire('/dashboard', 'dashboard'); // should be 'pages::dashboard'
```
**Why:** `pages::` keeps full-page components distinguishable from reusable UI components (cards, modals) at a glance.

---

## 4. State & Data Binding

```php
// Correct — deferred by default, validated before persistence
public string $email = '';

public function save()
{
    $validated = $this->validate(['email' => ['required', 'email', 'unique:users,email']]);
    User::create($validated);
}
```

```php
// Wrong — live binding straight to a mass-assignable model field, no validation
public User $user;
// <input wire:model.live="user.email"> then later: $this->user->save();
```
**Why:** Binding UI state directly to a persisted model without a validation gate lets unvalidated browser input reach the database — the same risk called out in the Fortify auth guide.

```php
// Correct — deferred wire:model (v4 default) for ordinary form fields
<input wire:model="title">

// Correct — .live only when every keystroke needs a server round trip
<input wire:model.live="searchQuery">

// Wrong — .live on every field "just in case"
<input wire:model.live="title">
<input wire:model.live="description">
```

---

## 5. Islands vs Child Components

```blade
{{-- Correct — island for pure render isolation --}}
<div>
    @island
        <div>
            Revenue: {{ $this->revenue }}
            <button wire:click="$refresh">Refresh</button>
        </div>
    @endisland
</div>
```

```php
// Wrong — extracting a full child component with props/events
// just to isolate a small re-rendering region
class RevenueWidget extends Component
{
    public $revenue;
    // all the overhead of a separate component, for isolation an island would give for free
}
```
**Why:** Islands give render isolation without the overhead of prop-passing and component boundaries — reserve child components for genuine reusability across pages.

---

## 6. Slots & Attribute Forwarding

```blade
{{-- Correct — slot for reactive parent-controlled content --}}
<livewire:card :$post>
    <h2>{{ $post->title }}</h2>
    <button wire:click="delete({{ $post->id }})">Delete</button>
</livewire:card>
```

```blade
{{-- Correct — attribute forwarding for styling passthrough --}}
<livewire:post.show :$post class="mt-4" />
<div {{ $attributes }}>...</div>
```

```blade
{{-- Wrong — inventing a custom prop just to pass a CSS class through --}}
<livewire:post.show :$post :extra-class="'mt-4'" />
```

---

## 7. CSS & JS Scoping

```blade
{{-- Correct — component styles scoped automatically --}}
<style>
.title { color: blue; }
</style>
```

```css
/* Wrong — component-specific styles dumped into a global stylesheet */
/* resources/css/app.css */
.counter-title { color: blue; }
```
**Why:** Global placement risks class-name collisions across unrelated components; scoped `<style>` blocks don't leak.

```blade
{{-- Correct — $js action for client-only behavior --}}
<script>
    this.$js.celebrate = () => { confetti() }
</script>
```
```php
// Wrong — a full server round trip for something with no server-side effect
public function celebrate()
{
    // triggers confetti() via a dispatched browser event, but nothing server-side changed
}
```

---

## 8. Loading States & Optimistic UI

```blade
{{-- Correct --}}
<button wire:click="save" class="data-loading:opacity-50">Save</button>
<div wire:show="$dirty">You have unsaved changes</div>
```

```php
// Wrong — manually toggling a boolean property for simple loading feedback
public bool $isSaving = false;

public function save()
{
    $this->isSaving = true;
    // ...
    $this->isSaving = false;
}
```
**Why:** `data-loading` and `wire:show` handle this natively without extra component state to manage and keep in sync.

---

## 9. Validation & Authorization

```php
// Correct — authorization enforced in the action method
public function delete($id)
{
    $post = Post::findOrFail($id);
    $this->authorize('delete', $post);
    $post->delete();
}
```

```blade
{{-- Wrong — "authorization" via hiding a button in Blade only --}}
@if(auth()->user()->isAdmin())
    <button wire:click="delete({{ $post->id }})">Delete</button>
@endif
```
```php
public function delete($id)
{
    Post::findOrFail($id)->delete(); // no server-side check — callable directly regardless of the hidden button
}
```
**Why:** Livewire actions are still directly callable server endpoints; hiding the trigger in the UI doesn't prevent a crafted request from calling `delete()` directly.

---

## 10. Sorting

```blade
{{-- Correct --}}
<ul wire:sort="reorder">
    @foreach ($items as $item)
        <li wire:key="{{ $item->id }}" wire:sort:item="{{ $item->id }}">{{ $item->title }}</li>
    @endforeach
</ul>
```

```blade
{{-- Wrong — looped elements without wire:key --}}
<ul wire:sort="reorder">
    @foreach ($items as $item)
        <li wire:sort:item="{{ $item->id }}">{{ $item->title }}</li>
    @endforeach
</ul>
```
**Why:** `wire:key` is required for Livewire's diffing to track elements correctly, independent of sorting.

---

## 11. Testing

```php
// Correct — asserts on state/output
Livewire::test(CreatePost::class)
    ->set('title', 'Hello')
    ->call('save')
    ->assertHasNoErrors()
    ->assertRedirect('/posts');
```

```php
// Wrong — asserting on implementation details
// e.g. mocking and asserting an internal method was called exactly once
// couples the test to internals instead of behavior
```

---

## 12. Interceptors

```js
// Correct — global concern registered once
Livewire.interceptRequest(({ onError }) => {
    onError(({ response, preventDefault }) => {
        if (response.status === 419) {
            preventDefault();
            if (confirm('Session expired. Refresh?')) window.location.reload();
        }
    });
});
```

```js
// Wrong — duplicating the same 419-handling logic inside every component's intercept()
this.intercept('save', ({ onError }) => {
    onError(({ response }) => {
        if (response.status === 419) { /* repeated per component */ }
    });
});
```

---

## 13. Migration Notes (v3 → v4)

```php
// Correct — leave stable v3 class-based components as-is
class CreatePost extends Component { /* unchanged, still works in v4 */ }
```

```php
// Wrong — bulk-converting the entire codebase to SFC in one PR
// large, high-risk diff with no incremental review checkpoints
```
**Why:** v4 is backwards compatible — convert per-component as you touch them (`php artisan livewire:convert`), not all at once. Also audit `wire:model` usages after upgrading, since the deferred-by-default behavior changed from v3.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Format choice | SFC for simple, MFC for complex — per the size/complexity table |
| Properties | Typed, camelCase |
| Page routing | `pages::` namespace via `Route::livewire()` |
| Persistence | Validated in the action method, never bound directly and saved |
| Render isolation | Islands over child components |
| Styling | Scoped `<style>`/`.css`, not global stylesheets |
| Loading feedback | `data-loading` / `wire:show`, not manual boolean toggles |
| Authorization | Enforced server-side in the action, not just hidden in Blade |
| Looped elements | Always `wire:key` |
| Testing | Assert behavior/output, not internals |
| App-wide request handling | Global `interceptRequest()`, not duplicated per component |
| v3→v4 migration | Incremental, per-component, not a bulk rewrite |