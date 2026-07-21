# Laravel Fortify — Sign-In / Sign-Up Data Handling Guide
**Stack:** Laravel Fortify (custom UI) · Session-based (web) auth

---

## 0. Assumptions
This guide assumes:
- `laravel/fortify` is installed and `FortifyServiceProvider` is registered in `bootstrap/providers.php` (Laravel 11+) or `config/app.php` (Laravel <11).
- You are **not** using Fortify's bundled views — you're wiring your own Blade/Inertia views to Fortify's backend actions.
- Standard `users` table (`id`, `name`, `email`, `password`, timestamps).

If your project deviates from this (custom guard, multi-tenant users table, UUID PKs, etc.), the steps below need adjustment — check your `config/fortify.php` and `App\Models\User` before applying.

---

## 1. Architecture Overview

Fortify separates **routes/controllers** (it provides these) from **business logic** (you provide these via Action classes). Data flows like this:

```
Request (form POST)
   → Fortify Route (auto-registered)
      → Fortify Controller (built-in)
         → Your Action Class (you write this)
            → Validation
            → Model interaction
            → Response
```

You never write controllers for login/register — you write **Action classes** bound in `FortifyServiceProvider`.

---

## 2. Enable Features

`config/fortify.php`:

```php
'features' => [
    Features::registration(),
    Features::resetPasswords(),
    Features::emailVerification(), // enable if you require verified email before login
    // Features::twoFactorAuthentication(...), // optional, add later
],
```

`config/fortify.php` — confirm views are disabled since you're using a custom UI:

```php
'views' => true, // set false ONLY if you register your own routes for these views manually
```
If `false`, you must define GET routes yourself that return your Blade/Inertia login & register pages.

---

## 3. Sign-Up: Data Handling

### 3.1 Create the Action class

```bash
php artisan make:action CreateNewUser
```
Fortify convention expects: `app/Actions/Fortify/CreateNewUser.php` implementing `Laravel\Fortify\Contracts\CreatesNewUsers`.

### 3.2 Validate Input (never trust raw request data)

```php
<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Rules\Role; // remove if not using Jetstream roles

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input): User
    {
        Validator::make($input, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ])->validate();

        return User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
```

**Data handling rules applied here:**
| Concern | How it's handled |
|---|---|
| Mass assignment | `User::create()` only accepts fields listed in `$fillable` on the model — never pass `$input` directly without whitelisting via validation first |
| Password storage | Always `Hash::make()` — never store plaintext. Fortify/Laravel default is bcrypt (or argon2id if configured in `config/hashing.php`) |
| Email uniqueness | Enforced at both DB level (`unique` index — verify this exists in your migration) and validation level |
| Password strength | Use `Password::defaults()` — configure once in `AppServiceProvider::boot()` (see §3.3) |

### 3.3 Centralize Password Rules

In `app/Providers/AppServiceProvider.php`:

```php
use Illuminate\Validation\Rules\Password;

public function boot(): void
{
    Password::defaults(function () {
        return Password::min(8)
            ->mixedCase()
            ->numbers()
            ->uncompromised(); // checks against HaveIBeenPwned breach corpus
    });
}
```

### 3.4 Bind the Action in FortifyServiceProvider

```php
// app/Providers/FortifyServiceProvider.php
use App\Actions\Fortify\CreateNewUser;
use Laravel\Fortify\Fortify;

public function boot(): void
{
    Fortify::createUsersUsing(CreateNewUser::class);
}
```

### 3.5 Confirm the users table columns match

```bash
php artisan tinker
>>> \Schema::getColumnListing('users')
```
Ensure `name`, `email`, `password` exist and match what your Action writes. If your form collects extra fields (phone, terms acceptance, etc.), add them to:
1. The migration (`php artisan make:migration add_phone_to_users_table`)
2. `$fillable` on `App\Models\User`
3. The validation array in `CreateNewUser`

---

## 4. Sign-In: Data Handling

### 4.1 Default Behavior
Out of the box, Fortify's `AuthenticatedSessionController` handles login using Laravel's `Auth::attempt()` against `email` + `password`, with:
- Rate limiting (throttle) by default — 5 attempts per minute per email+IP combo (`config/fortify.php` → `limiters.login`)
- Session regeneration on success (prevents session fixation)
- CSRF protection (automatic via Laravel's `web` middleware group)

No Action class is required for basic login **unless** you need custom logic (e.g., login by username OR email, checking `is_active` flag, etc.).

### 4.2 Custom Authentication Logic (if needed)

If you need custom rules — e.g., blocking unverified/banned accounts, allowing username login — use `Fortify::authenticateUsing()`:

```php
// FortifyServiceProvider::boot()
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Fortify::authenticateUsing(function (Request $request) {
    $request->validate([
        'email'    => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return null; // Fortify converts this to a validation error automatically
    }

    if (! $user->is_active) {
        return null; // or throw a specific ValidationException with a custom message
    }

    return $user;
});
```

**Data handling rules applied here:**
| Concern | How it's handled |
|---|---|
| Credential comparison | Never compare plaintext — always `Hash::check()` |
| Timing attacks | `Hash::check()` is constant-time by design; don't short-circuit with `if (!$user) return null` before running `Hash::check` in a way that creates a timing gap (minor risk, but keep the check flow consistent) |
| Rate limiting | Configured in `config/fortify.php` → `limiters.login`. Confirm it points to a defined limiter in `RouteServiceProvider` or `AppServiceProvider` |
| Session fixation | Handled automatically by Fortify's controller (`$request->session()->regenerate()`) — do not bypass this if writing a fully custom login flow |

### 4.3 Throttling Config Check

`config/fortify.php`:
```php
'limiters' => [
    'login' => 'login',
],
```

Confirm the `login` limiter is registered, typically in `app/Providers/AppServiceProvider.php`:
```php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('login', function (Request $request) {
    $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());
    return Limit::perMinute(5)->by($throttleKey);
});
```

---

## 5. Email Verification (if enabled)

If `Features::emailVerification()` is active:
- `User` model must implement `MustVerifyEmail`
- Unverified users are redirected to a "verify email" notice — gate protected routes with `verified` middleware:

```php
Route::middleware(['auth', 'verified'])->group(function () {
    // protected routes
});
```

---

## 6. Route Wiring for Custom Views (since `views => false`)

If you set `'views' => false` in `config/fortify.php`, register your own GET routes:

```php
// routes/web.php
Route::get('/login', fn () => view('auth.login'))->middleware('guest')->name('login');
Route::get('/register', fn () => view('auth.register'))->middleware('guest')->name('register');
```

Fortify auto-registers the **POST** routes (`/login`, `/register`, `/logout`, etc.) — do not redefine those manually or you'll get route conflicts.

---

## 7. Testing Checklist

```bash
php artisan test --filter=AuthenticationTest
php artisan test --filter=RegistrationTest
```

Verify manually or via Pest/PHPUnit:
- [ ] Duplicate email registration is rejected
- [ ] Weak passwords are rejected per `Password::defaults()`
- [ ] Password is hashed in DB (never plaintext — check via `tinker`)
- [ ] Wrong password on login returns generic error (don't leak "email not found" vs "wrong password" — avoids user enumeration)
- [ ] Rate limiting kicks in after threshold attempts
- [ ] Session ID changes after login (`session_regenerate` — check via browser devtools cookie value before/after)
- [ ] CSRF token required (test a POST without `_token` → expect 419)

---

## 8. Common Pitfalls

| Pitfall | Fix |
|---|---|
| Passing `$request->all()` directly to `User::create()` | Always validate first, build explicit array |
| Custom fields not in `$fillable` | Silently dropped — add to model |
| Forgetting `unique:users,email` | Allows duplicate accounts, breaks login uniqueness assumption |
| Not using `Password::defaults()` | Inconsistent password rules across register/reset flows |
| Revealing "email not found" vs "invalid password" | Enables account enumeration — return same generic message for both |
| Skipping session regeneration in fully custom login | Leaves app vulnerable to session fixation |

---

## Next Steps Not Covered Here
- Two-factor authentication (`Features::twoFactorAuthentication()`)
- Social login (separate from Fortify — typically Laravel Socialite)
- API token issuance (Sanctum) if you later add a mobile/API client

Flag if you want any of these added as a follow-up section.