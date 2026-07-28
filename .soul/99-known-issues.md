---
type: known-issues
open_count: 5
critical_count: 0
tracked_in: none
last_updated: 2026-07-28
---

# Known Issues

| Issue | Location | Severity |
|---|---|---|
| Phase 5 (Results) not built — assessment results are session-only, no UserReport persistence yet | Assessment flow | Medium |
| Queue worker must be running for email delivery but no supervisor/Procfile config exists | Deployment | Medium |
| No factories exist for domain models — only UserFactory is defined | database/factories/ | Low |
| OpenAI integration originally planned (gpt-4o-mini) — migrated to DeepSeek mid-project | app/Services/DeepSeekService | Low |
| Phase 3 endpoints not implemented: PDF export, admin report export, admin panel data, restriction save/reset | app/Http/Controllers/ | Low |

## Undocumented / Unconfirmed

None — all ADR files have been created (ADR-001 through ADR-005).
