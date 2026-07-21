# Laravel Conventions — Validation

**Laravel version reference:** 12.x / 13.x

---

## 1. Form Requests Over Inline Validation

Inline `$request->validate()` is acceptable only for trivial, 1-2 field, single-use cases. Anything more belongs in a Form Request.

```php
// Acceptable for trivial cases
public function search(Request $request)
{
    $request->validate(['q' => 'required|string|max:255']);
    // ...
}
```

```php
// Correct — non-trivial validation extracted to a Form Request
class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Order::class);
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'shipping_address' => ['required', 'array'],
            'shipping_address.street' => ['required', 'string', 'max:255'],
            'shipping_address.city' => ['required', 'string', 'max:100'],
        ];
    }
}

// controller
public function store(StoreOrderRequest $request)
{
    $validated = $request->validated();
    // ...
}
```

```php
// Wrong — non-trivial validation inline, no reuse, no separation of concerns
public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1|max:100',
        'shipping_address' => 'required|array',
        'shipping_address.street' => 'required|string|max:255',
        'shipping_address.city' => 'required|string|max:100',
    ]);
    // ...
}
```

---

## 2. One Form Request Per Action

```php
php artisan make:request StoreOrderRequest
php artisan make:request UpdateOrderRequest
```

```php
// Wrong — one shared request branching on method
class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        if ($this->isMethod('PUT')) {
            return ['quantity' => ['sometimes', 'integer', 'min:1']];
        }
        return ['product_id' => ['required', 'exists:products,id'], 'quantity' => ['required', 'integer', 'min:1']];
    }
}
```

**Why:** as rules diverge over time, a shared conditional request becomes harder to read and test than two small dedicated ones.

---

## 3. Authorization Belongs in the Form Request (or Policy), Not the Controller

```php
// Correct
class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('order'));
    }
}
```

```php
// Wrong — authorization checked separately/inconsistently in the controller
public function update(Request $request, Order $order)
{
    if ($order->user_id !== auth()->id()) {
        abort(403);
    }
    $request->validate([...]);
}
```

**Rule:** Centralizing authorization in the Form Request (or a Policy it calls) means it can't be forgotten when a new route/method reuses the same request.

---

## 4. Custom Rule Classes for Repeated Logic

```bash
php artisan make:rule StrongPassword
```

```php
class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/', $value)) {
            $fail('The :attribute must be at least 8 characters with an uppercase letter and a number.');
        }
    }
}

// usage
'password' => ['required', new StrongPassword],
```

```php
// Wrong — same regex/logic copy-pasted across every request that needs it
'password' => ['required', 'regex:/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/'],
```

---

## 5. Validation Messages & Attributes

Override only when the default message is genuinely unclear to the end user — don't override every field by default.

```php
class StoreOrderRequest extends FormRequest
{
    public function messages(): array
    {
        return [
            'quantity.max' => 'You can order a maximum of 100 units per request.',
        ];
    }
}
```

---

## Quick Reference

| Concern | Convention |
|---|---|
| Validation location | Form Request, except trivial 1-2 field inline cases |
| Reused validation across create/update | Two separate Form Requests, not one branching request |
| Authorization | In `authorize()` on the Form Request or via Policy — not scattered `if` checks in controllers |
| Repeated custom logic | Custom Rule class |
| Error messages | Override only where the default is unclear |