---
path: app/Models/UserReport.php
type: model
table: user_reports
fillable: [church_code, email, name, contact_no, music, technology, writing, technical, speaking, accounting, mentoring, bible_knowledge, eligible_ministry, ai_interpretation, gender, age, marital_status, baptized, time_in_faith, time_of_submission]
hidden: []
casts: []
traits: []
relationships: []
scopes: []
observers: []
status: draft
last_updated: 2026-07-28
---

# UserReport

## Purpose
Stores submitted assessment results from assessees (not admin users). Captures demographics, skill self-ratings, computed eligible ministries, and the DeepSeek AI-generated interpretation.

## Relationships
None — this is a standalone record that captures a snapshot of an assessment submission. Not linked to the User model.

## Notable behavior
- No Eloquent relationships defined — purely a data record.
- `eligible_ministry` stores comma-separated ministry IDs or names as text.
- `ai_interpretation` stores the DeepSeek AI-generated natural-language summary.
- `church_code` links the report to a specific church admin but is not a foreign key (plain indexed string).

## References
- [Laravel Eloquent — Getting Started](https://laravel.com/docs/eloquent)
- [Laravel Eloquent — Mutators & Casts](https://laravel.com/docs/eloquent-mutators)
- [Laravel Eloquent — Scopes](https://laravel.com/docs/eloquent#local-scopes)
- [Laravel Eloquent — Relationships](https://laravel.com/docs/eloquent-relationships)
- [Laravel Eloquent — Observers](https://laravel.com/docs/eloquent#observers)
- [Laravel Eloquent — Serialization](https://laravel.com/docs/eloquent-serialization)
