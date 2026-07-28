---
path: app/Http/Controllers/Auth/LogoutController.php
type: controller
model: none
methods: [logout]
routes:
  - name: admin.logout
    method: POST
    uri: /admin/logout
middleware: [admin]
form_requests: []
resources_used: []
status: draft
last_updated: 2026-07-28
---

# LogoutController

## Purpose
Handles admin logout by invalidating the session.

## Methods
- `logout(Request)` — Logs out the authenticated admin and redirects to the login page.

## Authorization
Protected by `admin` middleware.

## References
- [Laravel Controllers](https://laravel.com/docs/controllers)
- [Laravel Controller Middleware](https://laravel.com/docs/controllers#controller-middleware)
- [Laravel Resource Controllers](https://laravel.com/docs/controllers#resource-controllers)
- [Laravel Dependency Injection](https://laravel.com/docs/controllers#dependency-injection-and-controllers)
