# Laravel Conventions — API Response Conventions

**Laravel version reference:** 12.x / 13.x

---

## 1. Consistent Success/Error Envelope Shape

```php
// Correct — every success response uses the same top-level shape
return response()->json([
    'data' => OrderResource::make($order),
], 200);

return response()->json([
    'data' => OrderResource::collection($orders),
    'meta' => ['total' => $orders->total()],
], 200);
```

```php
// Correct — every error response uses the same top-level shape (see error-handling guide)
return response()->json([
    'error' => [
        'message' => 'The given data was invalid.',
        'code' => 'validation_failed',
        'errors' => $validator->errors(),
    ],
], 422);
```

```php
// Wrong — inconsistent shape between endpoints
return response()->json($order); // no envelope at all
return response()->json(['success' => true, 'result' => $order]); // different envelope in another controller
```

**Rule:** Define one envelope for success (`data`, optional `meta`) and one for errors (`error.message`, `error.code`, optional `error.errors`) and use them for every JSON API endpoint without exception. Use API Resources (`JsonResource`) to guarantee the `data` shape is consistent per resource type — never return raw models or ad hoc arrays from a controller.

---

## 2. HTTP Status Code Usage — 422 vs 400 vs 409

```php
// Correct
return response()->json([...], 422); // validation failure — malformed/invalid input
return response()->json([...], 400); // malformed request the server can't process (bad JSON, missing required param structurally)
return response()->json([...], 409); // valid request, but conflicts with current resource state
return response()->json([...], 404); // resource doesn't exist
return response()->json([...], 403); // authenticated but not authorized
return response()->json([...], 401); // not authenticated
```

```php
// Wrong — using 400 for everything
return response()->json(['error' => 'Order already shipped'], 400); // this is a state conflict, not a bad request → 409
return response()->json(['error' => 'Email is required'], 400);     // this is a validation error → 422
```

**Rule:**
- **422 Unprocessable Entity**: the request is well-formed but fails validation (Laravel's `FormRequest`/`Validator` returns this by default — keep it).
- **400 Bad Request**: the request itself is malformed at the protocol/structural level (unparseable JSON, wrong content type).
- **409 Conflict**: the request is valid and the input is valid, but it conflicts with the current state of the resource (e.g. trying to cancel an already-shipped order).
- **401 vs 403**: 401 when there's no valid authentication; 403 when the user is authenticated but the Policy/Gate denies the action.
- Don't default to 400 or 500 for everything — pick the status that lets API consumers branch on the response without parsing the message body.

---

## 3. Versioning Strategy — `/api/v1/...`

```php
// Correct — routes namespaced by version, controllers namespaced to match
// routes/api.php
Route::prefix('v1')->namespace('App\Http\Controllers\Api\V1')->group(function () {
    Route::apiResource('orders', OrderController::class);
});
```

```
app/Http/Controllers/Api/V1/OrderController.php
app/Http/Resources/V1/OrderResource.php
```

```php
// Wrong — breaking changes shipped into the same unversioned endpoint
Route::apiResource('orders', OrderController::class); // no version, no way to evolve without breaking existing clients
```

**Rule:** Version at the URL path (`/api/v1/...`) from the very first release, even if there's only one version — retrofitting versioning after clients depend on an unversioned API is far more disruptive. Namespace controllers and Resources per version (`Api\V1`, `Api\V2`) so a new version can override just the endpoints that changed, and reuse the rest via inheritance or shared traits. Don't introduce a breaking change into an existing version — ship it as a new version instead.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Success shape | `{ "data": ..., "meta"?: ... }` via API Resources |
| Error shape | `{ "error": { "message", "code", "errors"? } }` |
| Validation failure | 422 |
| Malformed request | 400 |
| State conflict | 409 |
| Not authenticated / not authorized | 401 / 403 |
| Versioning | URL-prefixed (`/api/v1/`), namespaced controllers/Resources per version |