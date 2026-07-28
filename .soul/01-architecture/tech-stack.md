---
type: tech-stack
php_version: ^8.2
laravel_version: ^12.0
database: sqlite
cache_driver: database
queue_driver: database
key_packages:
  - livewire/livewire: ^4.3
  - laravel/tinker: ^2.10.1
  - @tabler/core: ^1.4.0
  - chart.js: ^4.5.1
frontend: blade+livewire+vanilla-js
last_updated: 2026-07-28
---

# Tech Stack

## Backend
PHP ^8.2, Laravel ^12.0. Key packages: Livewire 4 (toast component only), Laravel Tinker, Faker, Pint (linting), PHPUnit.

## Frontend
Blade templates + vanilla JS (assessment forms). Livewire 4 is used only for the `⚡toast-message` component. Tabler Core (Bootstrap 5 CSS framework) for admin dashboard UI. Chart.js + chartjs-plugin-datalabels for dashboard charts. Tabler Icons. Build tooling: Vite 7 + laravel-vite-plugin.

## Infrastructure
All drivers are database-backed: SQLite (default), database cache, database queue, database session. No Redis, no Memcached. Queue worker (`php artisan queue:listen`) must run for email delivery.

## References
- [PHP Docs](https://www.php.net/docs.php)
- [Composer](https://getcomposer.org/doc/)
- [Laravel Docs](https://laravel.com/docs)
- [Laravel Configuration](https://laravel.com/docs/configuration)
- [Laravel Database](https://laravel.com/docs/database)
- [Laravel Cache](https://laravel.com/docs/cache)
- [Laravel Queue](https://laravel.com/docs/queues)
- [Laravel Session](https://laravel.com/docs/session)
