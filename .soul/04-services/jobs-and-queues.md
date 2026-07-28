---
type: jobs-summary
path: app/Jobs
queue_connection: database
queues: []
jobs: []
horizon_enabled: false
last_updated: 2026-07-28
---

# Jobs & Queues

No `app/Jobs` directory exists. The database queue connection is configured but no custom jobs have been defined. The `jobs`, `job_batches`, and `failed_jobs` tables exist via the default migration.

The queue worker must run (`php artisan queue:listen`) for mailables (verification codes, temporary passwords) to be delivered.

## References
- [Laravel Queues](https://laravel.com/docs/queues)
- [Laravel Queues — Jobs](https://laravel.com/docs/queues#creating-jobs)
- [Laravel Queues — Retries & Timeouts](https://laravel.com/docs/queues#max-attempts)
- [Laravel Horizon](https://laravel.com/docs/horizon)
- [Laravel Batches](https://laravel.com/docs/queues#job-batching)
- [Laravel Failed Jobs](https://laravel.com/docs/queues#failed-jobs)
