# Tabler UI — Integration Conventions (Laravel + Vite, `app.css` import)

**Package:** `@tabler/core` (current npm package — old `tabler-ui`/`tabler-react` names are deprecated). Verify version: `npm list @tabler/core`.

---

## 1. Install via npm, Never Vendor Manually

```bash
# Correct
npm install @tabler/core
```

```css
/* Wrong — compiled CSS hand-copied into the repo */
/* resources/css/vendor/tabler.min.css (copy-pasted from a CDN download) */
```
**Why:** Vendoring by hand defeats dependency tracking — `npm update` won't touch it, and the copy silently drifts from upstream security/bug fixes.

---

## 2. Import Order in `app.css`

```css
/* Correct — resources/css/app.css */
@import "@tabler/core/dist/css/tabler.min.css";

/* your overrides AFTER the import */
.btn-brand { background-color: #1a73e8; }
```

```css
/* Wrong — overrides placed before the Tabler import */
.btn-brand { background-color: #1a73e8; }
@import "@tabler/core/dist/css/tabler.min.css"; /* Tabler's own .btn styles now win the cascade */
```
**Why:** `@import` resolves inline in source order — anything before it gets overridden by Tabler's own rules that follow.

---

## 3. JS Import — Bundle, Don't Double-Load

```js
// Correct — resources/js/app.js
import "@tabler/core/dist/js/tabler.min.js";
```

```blade
{{-- Wrong — CDN script tag left in the layout alongside the npm-bundled import --}}
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
@vite(['resources/js/app.js'])  {{-- app.js ALSO imports tabler.min.js --}}
```
**Why:** Loading Tabler's JS twice (once via CDN, once via the Vite bundle) creates duplicate component instances — dropdowns/tooltips can double-fire or behave unpredictably.

---

## 4. Vite Config — Tabler Is Not a Separate Entry Point

```js
// Correct — vite.config.js
export default defineConfig({
    plugins: [
        laravel({ input: ['resources/css/app.css', 'resources/js/app.js'], refresh: true }),
    ],
});
```

```js
// Wrong — adding Tabler's dist files directly as a build entry
laravel({
    input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'node_modules/@tabler/core/dist/css/tabler.min.css', // don't do this
    ],
});
```
**Why:** Tabler is a dependency consumed *by* `app.css`/`app.js` via `@import`/`import`, not a standalone asset Vite should build separately.

---

## 5. Blade Loading — One Path, Not Both

```blade
{{-- Correct --}}
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
```

```blade
{{-- Wrong — CDN <link> left in place alongside the Vite-bundled CSS --}}
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" />
    @vite(['resources/css/app.css'])
</head>
```

---

## 6. Icons — Separate Package, Consistent Method

```bash
# Correct
npm install @tabler/icons
```

```blade
{{-- Wrong — assuming icon classes exist just because @tabler/core is installed --}}
<i class="ti ti-user"></i>  {{-- fails silently if @tabler/icons was never installed --}}
```

```blade
{{-- Wrong — mixing integration methods in the same codebase --}}
<i class="ti ti-user"></i>          {{-- icon-font approach in one view --}}
<svg><use href="user.svg#icon"/></svg>  {{-- inline-SVG approach in another --}}
```
**Why:** Icons are a separate package from core Tabler UI. Pick one integration method (icon font, SVG sprite, or per-icon JS import) and apply it project-wide so icon usage stays predictable.

---

## 7. Framework Conflict: Tabler (Bootstrap) vs Tailwind

```php
// Correct — explicit decision documented, e.g. in vite.config.js comments or a README
// "This project standardizes on Tabler/Bootstrap. Tailwind is NOT used."
```

```blade
{{-- Wrong — both loaded globally with no scoping strategy --}}
@vite(['resources/css/app.css'])  {{-- imports Tabler (Bootstrap-based) --}}
{{-- Tailwind's preflight/reset also applied globally in the same app.css --}}
<div class="container row d-flex">  {{-- ambiguous: whose .container/.row wins? --}}
```
**Why:** Tabler is Bootstrap-based; Tailwind's reset and utility classes (`.container`, `.row`, `.d-flex`) collide with Bootstrap's own. Running both unscoped produces unpredictable, hard-to-trace visual bugs.

**If both are genuinely required:** scope one framework to specific components (CSS layers or a class prefix), don't apply both resets globally.

---

## 8. Customization — CSS Overrides vs SCSS Variables

```css
/* Correct for a few cosmetic tweaks — override after the compiled CSS import */
@import "@tabler/core/dist/css/tabler.min.css";
:root { --tblr-primary: #1a73e8; }
```

```scss
// Correct for deep, variable-level theming — use the SCSS source instead
// resources/css/app.scss (requires: npm install -D sass)
@import "@tabler/core/src/scss/tabler";
```

```css
/* Wrong — hunting through compiled CSS to override dozens of individual rules
   when the actual need is a systematic color/spacing change */
.btn-primary { background: #1a73e8; }
.badge-primary { background: #1a73e8; }
.text-primary { color: #1a73e8; }
/* ...repeated for every component touching the primary color */
```
**Why:** Overriding a Sass variable once (SCSS path) propagates everywhere Tabler uses it internally; overriding compiled CSS rule-by-rule means re-doing the work for every component that references the same color.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Installation | npm, never manually vendored |
| CSS import | `@import` first in `app.css`, overrides after |
| JS loading | Bundled via `app.js`, never alongside a duplicate CDN `<script>` |
| Vite config | Tabler consumed by existing entries, not a separate `input` |
| Blade | One loading path only (`@vite`), no leftover CDN tags |
| Icons | Separate `@tabler/icons` install, one integration method project-wide |
| Tailwind conflict | Explicit decision documented — never both applied globally unscoped |
| Theming | SCSS variables for systematic changes, compiled-CSS overrides only for small tweaks |