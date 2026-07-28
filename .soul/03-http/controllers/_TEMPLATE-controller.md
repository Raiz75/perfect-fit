---
path: app/Http/Controllers/{{ControllerName}}.php
type: controller
model: {{primary model this controller acts on, if any}}
methods: [{{public method names}}]
routes:
  - name: {{route name}}
    method: {{HTTP verb}}
    uri: {{uri}}
middleware: [{{middleware applied, from constructor or route definition}}]
form_requests: [{{FormRequest classes type-hinted in methods}}]
resources_used: [{{API Resource classes returned}}]
status: draft
last_updated: {{YYYY-MM-DD}}
---

<!-- AGENT: Copy this file to 03-http/controllers/<snake-case-name>.md for
     every controller in app/Http/Controllers/. Cross-reference routes.md
     to fill the `routes` field accurately — do not guess route names. -->

# {{ControllerName}}

## Purpose
{{what this controller is responsible for}}

## Methods
{{method name — what it does, one line each}}

## Authorization
{{any policy checks, gates, or middleware-based auth found}}

## References
- [Laravel Controllers](https://laravel.com/docs/controllers)
- [Laravel Controller Middleware](https://laravel.com/docs/controllers#controller-middleware)
- [Laravel Resource Controllers](https://laravel.com/docs/controllers#resource-controllers)
- [Laravel Dependency Injection](https://laravel.com/docs/controllers#dependency-injection-and-controllers)
