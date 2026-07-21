# Laravel Conventions — Structure & Responsibility

**Laravel version reference:** 12.x / 13.x (streamlined app structure since 11.x)

---

## 1. Keep Controllers Thin

Controllers should orchestrate, not implement. Validation goes in Form Requests; business logic goes in an Action or Service class.

```php
// Wrong — logic, validation, and persistence all inline
class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $total = $product->price * $request->quantity;

        $order = Order::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'total' => $total,
        ]);

        Mail::to(auth()->user())->send(new OrderConfirmation($order));

        return redirect()->route('orders.show', $order);
    }
}
```

```php
// Correct — thin controller, delegated concerns
class OrderController extends Controller
{
    public function store(StoreOrderRequest $request, CreateOrder $action)
    {
        $order = $action->handle($request->validated(), auth()->user());

        return redirect()->route('orders.show', $order);
    }
}
```

---

## 2. Form Requests Own Validation

One Form Request per validated action — not a shared request reused with conditionals.

```bash
php artisan make:request StoreOrderRequest
php artisan make:request UpdateOrderRequest
```

```php
class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // or a policy check
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
```

**Anti-pattern to avoid:**
```php
// Don't reuse one request with an `isUpdate` flag branching the rules
class OrderRequest extends FormRequest
{
    public function rules()
    {
        if ($this->isMethod('put')) { /* ... */ }
        else { /* ... */ }
    }
}
```
This gets harder to reason about as rules diverge — split them.

---

## 3. Actions / Services for Business Logic

A single-purpose class per business operation. Naming convention: verb-noun.

```php
// app/Actions/CreateOrder.php
class CreateOrder
{
    public function handle(array $data, User $user): Order
    {
        $product = Product::findOrFail($data['product_id']);

        $order = Order::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => $data['quantity'],
            'total' => $product->price * $data['quantity'],
        ]);

        Mail::to($user)->send(new OrderConfirmation($order));

        return $order;
    }
}
```

**Rule:** If a controller method has more than ~10-15 lines beyond validation/redirect, that's a signal the logic belongs in an Action.

---

## 4. Fat Models Are Fine — For Eloquent Concerns Only

Models can hold: relationships, scopes, accessors/mutators, casts. They should **not** hold unrelated business logic (sending emails, calling external APIs, orchestrating multi-step processes).

```php
// Correct — Eloquent-native concerns on the model
class Order extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending(Builder $query): void
    {
        $query->where('status', 'pending');
    }

    protected function total(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => number_format($value, 2),
        );
    }
}
```

```php
// Wrong — business orchestration doesn't belong on the model
class Order extends Model
{
    public function completeAndNotify()
    {
        $this->update(['status' => 'complete']);
        Mail::to($this->user)->send(new OrderComplete($this));
        Http::post('https://external-api.com/webhook', [...]);
    }
}
```

---

## 5. API Resources for API Responses

Never return raw Eloquent models/collections from an API-facing controller.

```php
// Correct
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'total' => $this->total,
            'status' => $this->status,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}

// controller
return new OrderResource($order);
return OrderResource::collection($orders);
```

```php
// Wrong — exposes every column, including ones that shouldn't be public
return response()->json($order);
```

---

## 6. Jobs for Anything That Shouldn't Block the Request

```php
// Correct
class SendOrderConfirmation implements ShouldQueue
{
    public function __construct(public Order $order) {}

    public function handle(): void
    {
        Mail::to($this->order->user)->send(new OrderConfirmation($this->order));
    }
}

// dispatch
SendOrderConfirmation::dispatch($order);
```

```php
// Wrong — blocks the HTTP response on a mail send
Mail::to($order->user)->send(new OrderConfirmation($order));
```

**Rule:** Anything involving external I/O (email, SMS, third-party APIs, heavy computation) that doesn't need to block the user's response → queue it.

---

## Directory Reference (Laravel 11+/12/13 streamlined structure)

```
app/
├── Actions/          (business operations, one class per action)
├── Http/
│   ├── Controllers/
│   ├── Requests/     (Form Requests)
│   └── Resources/    (API Resources)
├── Jobs/
├── Models/
├── Policies/
└── Providers/
```

No `app/Http/Kernel.php` or `app/Console/Kernel.php` in 11+ — middleware and console config live in `bootstrap/app.php`.