# Laravel Conventions — Logging

**Laravel version reference:** 12.x / 13.x

---

## 1. Log Channel Usage (`stack`, `daily`, Custom Channels per Concern)

```php
// config/logging.php — correct: dedicated channels per concern, aggregated via stack
'channels' => [
    'stack' => ['driver' => 'stack', 'channels' => ['daily', 'slack']],
    'daily' => ['driver' => 'daily', 'path' => storage_path('logs/laravel.log'), 'days' => 14],
    'payments' => ['driver' => 'daily', 'path' => storage_path('logs/payments.log'), 'days' => 90],
    'security' => ['driver' => 'daily', 'path' => storage_path('logs/security.log'), 'days' => 365],
],
```

```php
// Correct — routed to the concern-specific channel
Log::channel('payments')->info('Charge succeeded', ['order_id' => $order->id, 'charge_id' => $charge->id]);
Log::channel('security')->warning('Failed login attempt', ['email' => $request->input('email'), 'ip' => $request->ip()]);
```

```php
// Wrong — everything dumped into the default channel with no separation
Log::info('Charge succeeded for order ' . $order->id);
Log::info('Failed login for ' . $request->input('email'));
```

**Rule:** Use the default `stack` channel for general application logs. Create dedicated channels for concerns with distinct retention, audit, or alerting needs (`payments`, `security`, `webhooks`) — this lets retention periods and downstream alerting differ per concern (e.g. security logs kept longer, payment logs monitored more tightly) without filtering a single noisy combined log. Log structured context as an array (second argument), not string-concatenated into the message — this keeps logs machine-parseable.

---

## 2. What Should/Shouldn't Be Logged (Never Log Secrets/Passwords)

```php
// Correct — context is useful for debugging, no sensitive values included
Log::info('User updated payment method', [
    'user_id' => $user->id,
    'card_last_four' => $paymentMethod->last4,
]);
```

```php
// Wrong — logs the raw secret/credential
Log::info('User updated payment method', [
    'user_id' => $user->id,
    'card_number' => $request->input('card_number'), // full PAN in plaintext logs
]);
Log::debug('Login attempt', ['email' => $email, 'password' => $password]); // never
```

**Rule:** Never log passwords, full card numbers/CVVs, API keys/tokens, session IDs, or other credentials/secrets — even at `debug` level, even temporarily "for debugging." Laravel's exception handler already redacts common sensitive fields (`password`, `password_confirmation`) from exception context by default; extend `dontFlash`/context redaction in the exception handler for any custom sensitive field names specific to the app. When logging request data for debugging, log an allow-list of specific safe fields rather than the entire request payload.

---

## Quick Reference

| Concern | Convention |
|---|---|
| General app logs | `stack` / default channel |
| Concern-specific logs (payments, security, webhooks) | Dedicated channel, own retention policy |
| Log context | Structured array, not string concatenation |
| Passwords, tokens, full card numbers, secrets | Never logged, at any log level |
| Debug logging of request data | Explicit allow-list of safe fields only |