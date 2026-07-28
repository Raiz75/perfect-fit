---
type: known-issues
open_count: 2
critical_count: 0
tracked_in: none
last_updated: 2026-07-28
---

# Known Issues

| Issue | Location | Severity |
|---|---|---|
| Assessment is session-only — no results persistence beyond session until Phase 5 | Assessment flow not built | Medium |
| Queue worker must be running for email delivery but no supervisor config exists | Deployment | Medium |

## Undocumented / Unconfirmed

| Placeholder | File | Notes |
|---|---|---|
| ADR-001 (session-only assessment) | 07-decisions/ | Decision rationale known from AGENTS.md but no ADR file exists yet |
| ADR-002 (pinned seeder IDs) | 07-decisions/ | Frozen IDs requirement known from AGENTS.md but no ADR file exists yet |
| ADR-003 (DeepSeek AI integration) | 07-decisions/ | Architectural decision to use external AI for interpretations |
| ADR-004 (user ID 1 template pattern) | 07-decisions/ | CopyDefaults pattern — multi-tenant-like design |
| ADR-005 (Blade + vanilla JS over Livewire/Inertia) | 07-decisions/ | Assessment uses standard HTML POST, no JS framework |
