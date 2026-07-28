---
type: adr
id: ADR-005
title: "Blade + vanilla JS for assessment (no Livewire/Inertia)"
status: accepted
date: 2026-07-28
affects: [tech-stack.md, assessment-controller.md, routes.md]
last_updated: 2026-07-28
---

# Blade + vanilla JS for assessment (no Livewire/Inertia)

## Context
The admin dashboard uses Livewire 4 for the toast notification component. The assessment flow could have been built with Livewire, Inertia, or a full SPA framework. A technology choice was needed for the assessment pages specifically.

## Decision
Assessment pages use standard Blade templates with vanilla JavaScript. No Livewire, no Inertia, no SPA framework. Admin dashboard uses Livewire only for the `⚡toast-message` component. All assessment form submissions are standard HTML POST requests. This is explicitly stated as an architecture non-negotiable.

## Consequences
- **Pro**: Assessment pages are simple, fast to load, and easy to test.
- **Pro**: No JavaScript framework lock-in for the assessment flow.
- **Pro**: Works with JavaScript disabled.
- **Con**: Full page reloads between assessment phases.
- **Con**: Cannot use Livewire's real-time features for the assessment.
- **Con**: Vanilla JS for interactive elements (client-side validation, etc.) is written manually.

## References
- [Laravel Blade](https://laravel.com/docs/blade)
- [Livewire](https://livewire.laravel.com/)
