---
type: adr
id: ADR-004
title: "User ID 1 as default template (multi-tenant-like pattern)"
status: accepted
date: 2026-07-28
affects: [user.md, auth.md, register-controller.md, question-controller.md, restriction-controller.md]
last_updated: 2026-07-28
---

# User ID 1 as default template (multi-tenant-like pattern)

## Context
Each church admin needs a customizable set of assessment questions and ministry restrictions. Rather than building a full multi-tenant system, a simpler pattern was needed where new admins start with sensible defaults that they can later customize.

## Decision
Admin user ID=1 is the template user. The `CopyDefaults` action duplicates all restriction and question records (demographic restrictions, skill restrictions, and all question types) from user ID=1 to newly registered admins. Admin user ID=1 must always exist. Registration creates user ID=2, 3, etc. and copies from user ID=1. The reset functionality in `QuestionController` and `RestrictionController` also re-copies from user ID=1.

## Consequences
- **Pro**: Simple implementation without a multi-tenant database architecture.
- **Pro**: Easy onboarding — new admins get a complete, working configuration immediately.
- **Pro**: Reset-to-defaults feature is straightforward.
- **Con**: User ID=1 is a special system account that must never be deleted.
- **Con**: Not a true multi-tenant isolation — if user ID=1's data is corrupted, all new registrations inherit the corruption.
- **Con**: No per-role or per-church hierarchy beyond the single admin level.

## References
- [App\Actions\CopyDefaults](../../../app/Actions/CopyDefaults.php)
