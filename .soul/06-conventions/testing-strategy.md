---
type: testing-strategy
framework: phpunit
paths: [tests/Feature, tests/Unit]
coverage_target: not enforced
factories_required: true
ci_runs_on: []
last_updated: 2026-07-28
---

# Testing Strategy

PHPUnit (`^11.5.50`) with Feature and Unit test suites. Only boilerplate tests exist — no custom Feature or Unit tests written yet. The UserFactory is the only factory defined. No factories exist for domain models (Ministry, Skill, etc.).

Test environment uses SQLite `:memory:`, array cache/session, sync queue, and array mail driver (see `phpunit.xml`).

## References
- [Laravel Testing](https://laravel.com/docs/testing)
- [Pest PHP](https://pestphp.com/docs/)
- [PHPUnit](https://phpunit.de/documentation.html)
- [Laravel Factories](https://laravel.com/docs/eloquent-factories)
- [Laravel HTTP Tests](https://laravel.com/docs/http-tests)
- [Laravel Database Tests](https://laravel.com/docs/database-testing)
- [Laravel Mocking](https://laravel.com/docs/mocking)
