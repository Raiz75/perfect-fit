---
type: adr
id: ADR-001
title: "Session-only assessment flow (no API, no Livewire)"
status: accepted
date: 2026-07-28
affects: [routes.md, assessment-controller.md]
last_updated: 2026-07-28
---

# Session-only assessment flow (no API, no Livewire)

## Context
The 4-phase ministry fit assessment needs to collect demographic info, skill ratings, interests, and behavioral responses. Evaluation must determine whether to build this as an SPA/API, use Livewire, or use standard HTML form POSTs with session state.

## Decision
The assessment is implemented as standard Blade-rendered HTML forms with session-based state. No AJAX calls, no Livewire, no API endpoints. Each phase is a separate form POST to a dedicated route (`assessment.phase1.store`, `assessment.phase2.store`, etc.). Session holds the progression state.

## Consequences
- **Pro**: Simple, testable with standard Laravel HTTP tests.
- **Pro**: No JavaScript framework dependency on the assessment pages.
- **Pro**: Works without a queue worker.
- **Con**: Full page reloads between phases.
- **Con**: Session data is lost if the assessee clears cookies or the session expires.
- **Con**: Assessment results (UserReport) are not persisted until Phase 5 (Results feature is pending).

## References
- [Laravel Session](https://laravel.com/docs/session)
- [Laravel Blade](https://laravel.com/docs/blade)
