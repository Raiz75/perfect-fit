---
type: adr
id: ADR-003
title: "DeepSeek AI for assessment interpretation"
status: accepted
date: 2026-07-28
affects: [overview.md, tech-stack.md, external-integrations.md, assessment-controller.md]
last_updated: 2026-07-28
---

# DeepSeek AI for assessment interpretation

## Context
After completing the 4-phase assessment, a human-readable interpretation of the results (explaining why certain ministries are recommended) adds value. Building a rule-based explanation engine would be complex and brittle.

## Decision
Use DeepSeek's chat API (`deepseek-chat` model) to generate natural-language interpretations of assessment results. The `DeepSeekService` class wraps the HTTP call, bound as a singleton in `AppServiceProvider`. The API key and model are configured via `config/services.php` (read from `.env`). The interpretation is stored in `user_reports.ai_interpretation`.

## Consequences
- **Pro**: Rich, flexible explanations without complex rule-based logic.
- **Pro**: Easy to swap models or providers (service class is the only integration point).
- **Con**: Requires internet access and a valid DeepSeek API key.
- **Con**: API costs scale with assessment volume.
- **Con**: Response time adds latency to the assessment completion.
- **Con**: No offline fallback — if the API is unavailable, the interpretation is stored as null.

## References
- [DeepSeek API](https://platform.deepseek.com/)
- [Laravel HTTP Client](https://laravel.com/docs/http-client)
