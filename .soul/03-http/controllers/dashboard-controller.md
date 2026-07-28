---
path: app/Http/Controllers/Admin/DashboardController.php
type: controller
model: UserReport
methods: [index, getData]
routes:
  - name: admin.dashboard
    method: GET
    uri: /admin/dashboard
  - name: admin.dashboard.data
    method: GET
    uri: /admin/dashboard/data
middleware: [admin]
form_requests: []
resources_used: []
status: draft
last_updated: 2026-07-28
---

# DashboardController

## Purpose
Admin dashboard homepage showing assessment statistics and visualizations using Chart.js.

## Methods
- `index()` — Returns the dashboard view with initial data.
- `getData(Request)` — Returns JSON data for dashboard charts (reports over time, ministry distributions, demographic breakdowns).

## Authorization
Protected by `admin` middleware — only authenticated admins can access.

## References
- [Laravel Controllers](https://laravel.com/docs/controllers)
- [Laravel Controller Middleware](https://laravel.com/docs/controllers#controller-middleware)
- [Laravel Resource Controllers](https://laravel.com/docs/controllers#resource-controllers)
- [Laravel Dependency Injection](https://laravel.com/docs/controllers#dependency-injection-and-controllers)
