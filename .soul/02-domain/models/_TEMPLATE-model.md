---
path: app/Models/{{ModelName}}.php
type: model
table: {{table name}}
fillable: [{{from $fillable}}]
hidden: [{{from $hidden}}]
casts:
  {{field}}: {{cast type}}
traits: [{{used traits}}]
relationships:
  - {{relationType}}: {{RelatedModel}}
scopes: [{{local scopes found}}]
observers: [{{observer class if registered}}]
status: draft
last_updated: {{YYYY-MM-DD}}
---

<!-- AGENT: Copy this file to 02-domain/models/<snake-case-model-name>.md
     for every file in app/Models/. Read the actual model file for
     $fillable, $hidden, $casts, relationship methods, scopes, and any
     registered observers (check the model's boot() method or
     EventServiceProvider). Do not infer relationships that aren't
     defined as methods in the file. -->

# {{ModelName}}

## Purpose
{{one paragraph, plain language, what this model represents in the domain}}

## Relationships
{{list each relationship method and what it returns}}

## Notable behavior
{{custom accessors/mutators, scopes, observers, business logic in the model itself}}

## References
- [Laravel Eloquent — Getting Started](https://laravel.com/docs/eloquent)
- [Laravel Eloquent — Mutators & Casts](https://laravel.com/docs/eloquent-mutators)
- [Laravel Eloquent — Scopes](https://laravel.com/docs/eloquent#local-scopes)
- [Laravel Eloquent — Relationships](https://laravel.com/docs/eloquent-relationships)
- [Laravel Eloquent — Observers](https://laravel.com/docs/eloquent#observers)
- [Laravel Eloquent — Serialization](https://laravel.com/docs/eloquent-serialization)
