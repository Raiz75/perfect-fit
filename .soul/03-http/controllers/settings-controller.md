---
path: app/Http/Controllers/Admin/SettingsController.php
type: controller
model: User
methods: [index, updateChurchName, updatePassword]
routes:
  - name: admin.settings
    method: GET
    uri: /admin/settings
  - name: admin.settings.church-name
    method: POST
    uri: /admin/settings/church-name
  - name: admin.settings.password
    method: POST
    uri: /admin/settings/password
middleware: [admin]
form_requests: [ChangePasswordRequest]
resources_used: []
status: draft
last_updated: 2026-07-28
---

# SettingsController

## Purpose
Manages admin account settings: church name display and password changes.

## Methods
- `index()` — Returns the settings page.
- `updateChurchName(Request)` — Updates the admin's church name.
- `updatePassword(ChangePasswordRequest)` — Changes the admin's password (requires current password validation).

## Authorization
Protected by `admin` middleware.

## References
- [Laravel Controllers](https://laravel.com/docs/controllers)
- [Laravel Controller Middleware](https://laravel.com/docs/controllers#controller-middleware)
- [Laravel Resource Controllers](https://laravel.com/docs/controllers#resource-controllers)
- [Laravel Dependency Injection](https://laravel.com/docs/controllers#dependency-injection-and-controllers)
