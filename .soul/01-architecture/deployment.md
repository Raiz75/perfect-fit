---
type: deployment
env: [local]
queue_workers: false
scheduler_enabled: false
scheduled_tasks: []
containerized: false
ci_cd: none found
last_updated: 2026-07-28
---

# Deployment

## Environments
Only `local` is explicitly configured. No staging/production environment files found.

## Queue Workers
Not configured. The application requires `php artisan queue:listen` for email (verification codes, temporary passwords) but no supervisor/Processfile config is present.

## Scheduler
No scheduled tasks defined. `routes/console.php` contains only the default `inspire` command.

## CI/CD
No CI/CD configuration found (no `.github/workflows/`, `.gitlab-ci.yml`, or deploy scripts).

## References
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Laravel Scheduler](https://laravel.com/docs/scheduling)
- [Laravel Queues — Workers](https://laravel.com/docs/queues#running-the-queue-worker)
- [Laravel Forge](https://forge.laravel.com/docs)
- [Laravel Vapor](https://vapor.laravel.com/docs)
- [Laravel Envoyer](https://envoyer.io/docs)
