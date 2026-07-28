---
path: app/Http/Controllers/Auth/LoginController.php
type: controller
model: User
methods: [showLoginForm, login, checkSession]
routes:
  - name: admin.login
    method: GET
    uri: /admin/login
middleware: []
form_requests: [LoginRequest]
resources_used: []
status: draft
last_updated: 2026-07-28
---

# LoginController

## Purpose
Handles admin login with email and password.

## Methods
- `showLoginForm()` — Returns the login page view.
- `login(LoginRequest)` — Validates credentials via `LoginRequest`, authenticates the admin session.
- `checkSession(Request)` — Ajax endpoint to check if the current session is still valid.

## Authorization
Public — no middleware on login routes.

## References
- [Laravel Controllers](https://laravel.com/docs/controllers)
- [Laravel Controller Middleware](https://laravel.com/docs/controllers#controller-middleware)
- [Laravel Resource Controllers](https://laravel.com/docs/controllers#resource-controllers)
- [Laravel Dependency Injection](https://laravel.com/docs/controllers#dependency-injection-and-controllers)
