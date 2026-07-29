# Laravel Conventions — Directory Structure for Larger Apps

**Laravel version reference:** 12.x / 13.x

---

## 1. Domain/Module-Based Structure vs Default MVC-Flat Structure

```
// Default MVC-flat (Laravel's out-of-the-box structure)
app/
  Http/Controllers/OrderController.php
  Http/Controllers/ProductController.php
  Models/Order.php
  Models/Product.php
  Actions/PlaceOrder.php
  Actions/CalculateShipping.php
```

```
// Domain/module-based (grouped by business domain, not by technical layer)
app/
  Domain/
    Orders/
      Models/Order.php
      Actions/PlaceOrder.php
      Policies/OrderPolicy.php
      Http/Controllers/OrderController.php
      Events/OrderPlaced.php
    Products/
      Models/Product.php
      Actions/CalculateShipping.php
      Http/Controllers/ProductController.php
```

**Rule:** Both are valid — the choice depends on app size and team structure, not on one being objectively "more correct."
- **Default MVC-flat**: appropriate for small-to-medium apps, small teams, or early-stage products where domain boundaries are still shifting. It's what most Laravel developers expect on first read, minimizing onboarding friction.
- **Domain/module-based**: appropriate once the flat structure makes it hard to see what belongs together (e.g. `Http/Controllers/` has 40+ files with no visible grouping), multiple teams own different domains and want lower merge conflict/blast radius, or the app has clearly separable bounded contexts (Orders, Billing, Catalog) that rarely share code.

---

## 2. When to Introduce This (Team Size, App Complexity Threshold)

**Signals it's time to move to domain/module structure:**
- More than ~3 developers regularly working in the codebase simultaneously.
- `app/Models/`, `app/Http/Controllers/`, or `app/Actions/` each exceed roughly 15–20 files with no sub-grouping, and it's routinely hard to find "everything related to Orders."
- Two or more genuinely separable business domains exist with minimal cross-dependency (Orders vs. CMS content, for example).
- The team is starting to modularize toward eventual service extraction and wants domain boundaries enforced in code structure first.

**Signals to stay flat:**
- Single developer or small team (1–3).
- App is under active domain discovery — restructuring around domains that are still being figured out creates churn without benefit.
- App is small enough that `app/Models/`, `app/Http/Controllers/`, etc. each stay under ~15 files.

**Rule:** Don't restructure preemptively "for scale" on a new or small app — it adds indirection (deeper namespaces, more `use` statements, harder-to-follow autoloading) with no payoff until the team/complexity threshold above is actually hit. When migrating, do it incrementally by domain (move Orders first, leave the rest flat, migrate the next domain later) rather than a single big-bang restructure.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Small app / small team / early domain discovery | Default MVC-flat |
| Large app / multiple teams / stable domain boundaries | Domain/module-based |
| Migration approach | Incremental, one domain at a time — not big-bang |
| Threshold signal | Any single `app/` subfolder routinely exceeds ~15–20 unrelated files |