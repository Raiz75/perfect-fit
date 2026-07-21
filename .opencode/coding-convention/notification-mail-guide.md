# Laravel Conventions — Notifications & Mail

**Laravel version reference:** 12.x / 13.x

---

## 1. Notification vs Mailable — When Each Applies

```php
// Correct — Notification when it can go through multiple channels, or is tied to a User
class OrderShipped extends Notification implements ShouldQueue
{
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->subject('Your order has shipped')->line("Order #{$this->order->id} is on its way.");
    }
}

$user->notify(new OrderShipped($order));
```

```php
// Correct — Mailable when it's exclusively email with no other channel and no notifiable model
Mail::to($guestEmail)->send(new InvoiceMailable($invoice));
```

**Rule:** Use a **Notification** whenever the message could reasonably go through more than one channel (mail, database/in-app, broadcast, SMS), or when it's tied to a `Notifiable` model (typically `User`) and you want a persisted record (`database` channel) of what was sent. Use a **Mailable** directly only for pure transactional email with no notifiable recipient tracking needed (e.g. sending an invoice to an email address that isn't a user account) or system-level email unrelated to a specific user action.

---

## 2. Channel Selection (`mail`, `database`, `broadcast`)

```php
// Correct — channel choice matches how the user actually needs to receive it
public function via(object $notifiable): array
{
    return $notifiable->wants_email_notifications
        ? ['mail', 'database', 'broadcast']
        : ['database', 'broadcast'];
}
```

**Rule:**
- `mail`: anything the user needs a durable, off-platform record of (receipts, shipping confirmations, security alerts).
- `database`: anything that should show up in an in-app notification center/bell icon, regardless of whether it's also emailed.
- `broadcast`: anything that needs to update the UI in real time while the user is active (live status changes, chat messages) — pair with `database` so it's not lost if the user isn't currently connected.
- Respect user notification preferences in `via()` rather than hardcoding channels for every notification class.

---

## 3. Markdown Mail Conventions

```php
// Correct — markdown mailable for anything with reusable branding/layout
class OrderShipped extends Mailable
{
    public function content(): Content
    {
        return new Content(markdown: 'mail.orders.shipped');
    }
}
```

```blade
{{-- resources/views/mail/orders/shipped.blade.php --}}
@component('mail::message')
# Your order has shipped

Order #{{ $order->id }} is on its way, {{ $order->user->name }}.

@component('mail::button', ['url' => $trackingUrl])
Track Your Order
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
```

**Rule:** Use Laravel's Markdown mail components (`mail::message`, `mail::button`, `mail::table`) for all transactional email so styling, dark-mode support, and layout stay consistent app-wide without hand-rolled HTML/CSS in every template. Reserve raw Blade/HTML mail views for cases requiring pixel-precise custom design (marketing email, branded receipts) that the component system can't accommodate — and keep those in a clearly separate `resources/views/mail/custom/` path so the distinction is visible.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Multi-channel / user-tied message | Notification |
| Pure transactional email, no notifiable | Mailable |
| Durable off-platform record | `mail` channel |
| In-app notification center | `database` channel |
| Real-time UI update | `broadcast` (paired with `database`) |
| Styling | Markdown mail components by default |