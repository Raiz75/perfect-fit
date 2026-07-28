---
path: app/Http/Controllers/Auth/RegisterController.php
type: controller
model: User
methods: [showRegisterForm, sendVerification, verifyRegistration, validateChurchCode]
routes:
  - name: admin.register
    method: GET
    uri: /admin/register
  - name: admin.send-verification
    method: POST
    uri: /admin/send-verification
  - name: admin.verify-registration
    method: POST
    uri: /admin/verify-registration
middleware: []
form_requests: [SendVerificationRequest]
resources_used: []
status: draft
last_updated: 2026-07-28
---

# RegisterController

## Purpose
Handles admin registration with email verification (6-digit code). Generates a unique 9-character church code on successful registration. After registration, the `CopyDefaults` action duplicates all restrictions and questions from admin user ID=1 to the new user.

## Methods
- `showRegisterForm()` — Returns the registration page view.
- `sendVerification(SendVerificationRequest)` — Validates registration data, sends a 6-digit verification code via `VerificationCodeMail`.
- `verifyRegistration(Request)` — Verifies the 6-digit code, creates the admin user with a generated church code, runs `CopyDefaults`.
- `validateChurchCode(Request)` — Ajax endpoint to check if a church code is valid.

### Private helpers
- `generateChurchCode()` — Generates a random 9-character alphanumeric church code.

## Authorization
Public — no middleware on registration routes.

## References
- [Laravel Controllers](https://laravel.com/docs/controllers)
- [Laravel Controller Middleware](https://laravel.com/docs/controllers#controller-middleware)
- [Laravel Resource Controllers](https://laravel.com/docs/controllers#resource-controllers)
- [Laravel Dependency Injection](https://laravel.com/docs/controllers#dependency-injection-and-controllers)
