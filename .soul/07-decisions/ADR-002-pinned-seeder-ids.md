---
type: adr
id: ADR-002
title: "Pinned seeder IDs for ministries, categories, and skills"
status: accepted
date: 2026-07-28
affects: [database-schema.md, seeders, all restriction/question models]
last_updated: 2026-07-28
---

# Pinned seeder IDs for ministries, categories, and skills

## Context
Ministries (29), ministry categories (6), and skills (8) are seeded with explicit IDs. Foreign keys in demographic restrictions, skill restrictions, and all question types reference these IDs. The `CopyDefaults` action and all restriction/question management logic depend on these IDs being stable.

## Decision
Seeder IDs are frozen and must never change. Ministries are seeded with IDs 1-29, categories with 1-6, and skills with 1-8. Seeders use explicit `id` values rather than auto-increment. No new migration may alter the order or values of these seeded IDs.

## Consequences
- **Pro**: Foreign key relationships are predictable and don't require database queries to resolve.
- **Pro**: Default data (from user ID=1) can be reliably copied via `CopyDefaults`.
- **Con**: Adding a new ministry/category/skill requires finding the next available ID rather than relying on auto-increment.
- **Con**: Tests that truncate and re-seed must preserve the same IDs.

## References
- [Laravel Seeders](https://laravel.com/docs/seeding)
