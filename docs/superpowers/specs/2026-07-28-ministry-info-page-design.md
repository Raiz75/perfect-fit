# Ministry Info Page (/ministries) — Design Spec

## Overview

A detailed ministry information page at `/ministries` showing all 29 ministries grouped by their 6 categories. Each ministry displays a full description paragraph and a requirements table (pulled from the default restrictions of admin user ID 1). The page is a modern scrollable single-page layout using the new PERFIT design language (purple #8c52ff palette, glassmorphism, smooth animations).

## Data Layer

- **Migration edit**: Add `description` (TEXT, nullable) column to existing `0001_01_01_000004_create_ministries_table.php`
- **Seeder**: `MinistryDescriptionSeeder` — populates `description` for all 29 ministries with text from old `ministry.html`
- **Controller data**: `FrontendController::ministries()` loads:
  - All `MinistryCategory` with their `ministries` (eager-loaded)
  - `DemographicRestriction` where `user_id = 1`, keyed by `ministry_id`
  - `SkillRestriction` where `user_id = 1`, keyed by `ministry_id`
  - `Skill` model (all 8 skills) to map boolean skill columns to skill names

## Page Structure

1. **Top nav** — shared `_partials/topnav.blade.php` (no changes needed)
2. **Hero section** — "Ministries" heading with subtitle: "Discover where your gifts can make a difference"
3. **Category sections** — 6 sections, one per category, stacked vertically (scrollable):
   - Category heading with color accent matching the category color scheme
   - Horizontal carousel containing ministry slides
4. **Each ministry slide** in a carousel:
   - Ministry name (h3)
   - Full description paragraph (from `description` column)
   - Requirements table: Gender | Age | Status | Baptized | Time in Faith | Skills
5. **Footer** — shared `_partials/footer.blade.php`

## Carousel Implementation

- Each category has its own independent carousel (vanilla JS, no Bootstrap dependency for this component)
- Prev/next buttons + dot indicators
- Smooth slide transition (CSS `transform: translateX`)
- Responsive: single slide visible at all breakpoints (each slide = full card)

## Requirements Table Logic

- Look up `DemographicRestriction` for the ministry (from user ID 1)
- Fields: `gender`, `age_min`-`age_max`, `marital_status`, `baptized` (boolean → "Yes"/"No"), `time_in_faith`
- Look up `SkillRestriction` for the ministry (from user ID 1)
- Map boolean skill columns (e.g., `writing`, `public_speaking`) to skill names from `skills` table
- Display comma-separated skill names

## Visual Design

- Color palette: matches existing PERFIT design (purple `#8c52ff`, gradients, glassmorphism)
- Category color coding (same as landing page): Core=#8c52ff, Support=#2dce89, Outreach=#fb6340, Creative=#f5365c, Care=#11cdef, Special Interest=#2dce89
- Cards: white/glass background, rounded corners (18px), subtle shadow, hover lift
- Animations: IntersectionObserver reveal on scroll, carousel transitions

## Files to Modify/Create

| File | Action |
|------|--------|
| `database/migrations/0001_01_01_000004_create_ministries_table.php` | Edit: add `description` column |
| `database/seeders/MinistryDescriptionSeeder.php` | Create: 29 descriptions |
| `app/Http/Controllers/FrontendController.php` | Edit: pass data to view |
| `resources/views/ministries.blade.php` | Create: full page layout |
| `resources/css/app.css` | Edit: add ministry page styles |

## Out of Scope

- Phase 5 (Results) integration — the `/ministries` page is read-only info
- Any form submissions, AJAX, or Livewire
- Editing descriptions via admin panel (static content for now)
