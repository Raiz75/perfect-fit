---
type: agent-instructions
audience: ai-agent
---

# How to use this template

This is a scaffold for documenting a Laravel codebase as an Obsidian vault.
Every `.md` file in this folder is a template with `{{PLACEHOLDER}}` tokens
and an `<!-- AGENT: ... -->` comment describing what to fill in and where
to find the source of truth in the codebase.

## Process

1. **Copy this whole folder** into the target project as `docs-vault/` (or
   wherever the user wants it).
2. **Do not guess.** Every placeholder must be filled from an actual file
   read (composer.json, routes files, model files, migrations, etc). If a
   value can't be confirmed, leave the placeholder and flag it in
   `99-known-issues.md` under an "Undocumented" section instead of
   inventing a value.
3. **Model & controller files are 1-per-file.** For every file in
   `app/Models/`, copy `02-domain/models/_TEMPLATE-model.md` to
   `02-domain/models/<snake-case-name>.md` and fill it in. Same pattern for
   `03-http/controllers/_TEMPLATE-controller.md` and
   `07-decisions/_TEMPLATE-ADR.md` (one per architectural decision, only
   created when a decision is actually documented/found, not speculative).
4. **Fill `00-INDEX.md` last**, after all other notes exist, so the links
   and one-line summaries are accurate.
5. **Update `last_updated` frontmatter field** on every file you touch, in
   `YYYY-MM-DD` format, using the actual date of generation.
6. **Never delete or overwrite user-edited notes** without confirmation —
   if a note already exists and has content beyond the template
   placeholders, treat it as user-authored and ask before changing it.

## Naming conventions for generated files

- Models: `app/Models/OrderItem.php` → `02-domain/models/order-item.md`
- Controllers: `app/Http/Controllers/OrderController.php` →
  `03-http/controllers/order-controller.md`
- ADRs: sequential, `ADR-001-`, `ADR-002-`, ... never reuse a number.

## What NOT to do

- Do not fabricate relationships, routes, or fields not present in code.
- Do not mark `status: stable` unless there's evidence (tests passing,
  in production use, etc) — default new entries to `status: draft`.
- Do not summarize business logic you haven't actually read.
