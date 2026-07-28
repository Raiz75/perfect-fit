---
type: convention
controller_suffix: Controller
request_suffix: Request
resource_suffix: (none — no API resources)
test_naming: camelCase methods
last_updated: 2026-07-28
---

# Naming Conventions

Observed patterns from actual code:
- **Controllers**: `{Name}Controller` — e.g. `FrontendController`, `AssessmentController`, `DashboardController`. Sub-namespaced under `Admin/`, `Assessment/`, `Auth/`.
- **Form Requests**: `{Verb}{Entity}Request` — e.g. `LoginRequest`, `StoreDemographicsRequest`, `ChangePasswordRequest`. Sub-namespaced under `Auth/`, `Assessment/`.
- **Models**: Singular PascalCase — `User`, `Ministry`, `MinistryCategory`, `UserReport`.
- **Migrations**: Laravel default snake_case with sequential timestamps.
- **Routes**: Kebab-case URI segments, dot-notation route names (e.g. `admin.questions.skill`, `assessment.phase1.store`).
- **Tests**: Default PHPUnit camelCase test methods.
- **Blade views**: Dot-notation namespaced by feature area (e.g. `admin.dashboard.index`, `assessment.phase1`).

## References
- [Laravel Naming Conventions](https://laravel.com/docs/controllers#restful-partial-resource-routes)
- [PSR-4 Naming](https://www.php-fig.org/psr/psr-4/)
- [Laravel Testing — Naming](https://laravel.com/docs/testing#naming-tests)
