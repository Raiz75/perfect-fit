---
path: app/Http/Controllers/FrontendController.php
type: controller
model: none
methods: [index, ministries, privacyPolicy]
routes:
  - name: home
    method: GET
    uri: /
  - name: ministries
    method: GET
    uri: /ministries
  - name: privacy-policy
    method: GET
    uri: /privacy-policy
middleware: []
form_requests: []
resources_used: []
status: draft
last_updated: 2026-07-28
---

# FrontendController

## Purpose
Handles public-facing pages (homepage, ministries listing, privacy policy). No authentication required.

## Methods
- `index()` — Returns the home/welcome page view.
- `ministries()` — Returns the ministries listing page.
- `privacyPolicy()` — Returns the privacy policy page.

## Authorization
No authorization — all methods are publicly accessible.

## References
- [Laravel Controllers](https://laravel.com/docs/controllers)
- [Laravel Controller Middleware](https://laravel.com/docs/controllers#controller-middleware)
- [Laravel Resource Controllers](https://laravel.com/docs/controllers#resource-controllers)
- [Laravel Dependency Injection](https://laravel.com/docs/controllers#dependency-injection-and-controllers)
