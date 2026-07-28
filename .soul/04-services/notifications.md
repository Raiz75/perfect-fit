---
type: notifications-summary
path: app/Notifications
channels: []
notifications: []
last_updated: 2026-07-28
---

# Notifications

No `app/Notifications` directory exists. The application uses Laravel Mailables instead (in `app/Mail/`) for email delivery:
- **VerificationCodeMail** — sends a 6-digit verification code during registration. View: `emails.verification-code`.
- **TemporaryPasswordMail** — sends a temporary password for password reset. View: `emails.temporary-password`.

Queue is required for email delivery — run `php artisan queue:listen`.

## References
- [Laravel Notifications](https://laravel.com/docs/notifications)
- [Laravel Notification Channels](https://laravel.com/docs/notifications#specifying-delivery-channels)
- [Laravel Mail](https://laravel.com/docs/mail)
- [Laravel Broadcasting](https://laravel.com/docs/broadcasting)
