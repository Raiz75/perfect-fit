---
path: app/Http/Controllers/Auth/ForgotPasswordController.php
type: controller
model: User
methods: [showForm, sendTempPassword]
routes:
  - name: admin.forgot-password
    method: POST
    uri: /admin/forgot-password
middleware: []
form_requests: []
resources_used: []
status: draft
last_updated: 2026-07-28
---

# ForgotPasswordController

## Purpose
Handles password reset by sending a temporary password via email (`TemporaryPasswordMail`).

## Methods
- `showForm()` — Returns the forgot password form view.
- `sendTempPassword(Request)` — Sends a temporary password to the admin's email.

## Authorization
Public — no middleware on forgot-password routes.

## References
- [Laravel Controllers](https://laravel.com/docs/controllers)
- [Laravel Controller Middleware](https://laravel.com/docs/controllers#controller-middleware)
- [Laravel Resource Controllers](https://laravel.com/docs/controllers#resource-controllers)
- [Laravel Dependency Injection](https://laravel.com/docs/controllers#dependency-injection-and-controllers)
