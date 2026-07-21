# Laravel Conventions — Blade / Views

**Laravel version reference:** 12.x / 13.x

---

## 1. Component Naming — `<x-...>` vs `@include`

```blade
{{-- Correct — reusable, self-contained UI with its own logic/props --}}
<x-alert type="error" :message="$errorMessage" />
<x-forms.input name="email" label="Email Address" />
```

```blade
{{-- Wrong — @include for something that takes structured props and is reused app-wide --}}
@include('partials.alert', ['type' => 'error', 'message' => $errorMessage])
```

**Rule:**
- Use `<x-...>` components when the fragment has its own props, slots, or logic (class-backed or anonymous), and is reused across multiple views.
- Use `@include` only for simple, one-off template splitting within a single view context (e.g. breaking up a long form into includes that all share the parent's variable scope) — not as a substitute for a component.
- Anonymous components (`resources/views/components/*.blade.php`, no class) are fine for pure-markup pieces with no logic; class-backed components (`php artisan make:component`) when you need a constructor, methods, or dependency injection.

---

## 2. Slot vs Prop Conventions

```blade
{{-- Correct — data goes through props, markup/content goes through slots --}}
<x-card :title="$post->title" :footer-class="'text-sm'">
    <p>{{ $post->excerpt }}</p>

    <x-slot:footer>
        <a href="{{ route('posts.show', $post) }}">Read more</a>
    </x-slot:footer>
</x-card>
```

```blade
{{-- Wrong — passing markup through a prop as a raw string --}}
<x-card :title="$post->title" :body="'<p>' . $post->excerpt . '</p>"'" />
```

**Rule:**
- Props: scalars, models, arrays, booleans — anything that configures behavior or displays as text.
- Slots: HTML/markup content, especially anything with nested Blade syntax, conditionals, or child components.
- Named slots (`<x-slot:name>`) for optional/secondary content areas (footer, header, actions); the default slot for primary content.

---

## 3. Layout Inheritance — `@extends`/`@section` vs Blade Components

```blade
{{-- Correct (preferred for new code) — component-based layout --}}
{{-- resources/views/components/layout.blade.php --}}
<html>
<body>
    <x-nav />
    <main>{{ $slot }}</main>
    <x-footer />
</body>
</html>

{{-- resources/views/posts/show.blade.php --}}
<x-layout>
    <h1>{{ $post->title }}</h1>
</x-layout>
```

```blade
{{-- Acceptable (legacy/existing codebases) — @extends/@section --}}
@extends('layouts.app')
@section('content')
    <h1>{{ $post->title }}</h1>
@endsection
```

**Rule:** For new projects, prefer component-based layouts — they compose more predictably and support props/slots for regions like `<title>` or breadcrumbs. `@extends`/`@section` is not wrong, but don't mix both patterns for the same layout within one codebase — pick one and apply it consistently.

---

## 4. Keeping Logic Out of Views — No Query Calls in Blade

```blade
{{-- Correct — data is already resolved before it reaches the view --}}
@foreach ($posts as $post)
    <x-post-card :post="$post" />
@endforeach
```

```blade
{{-- Wrong — query execution inside the view --}}
@foreach (\App\Models\Post::where('published', true)->get() as $post)
    <div>{{ $post->title }}</div>
@endforeach
```

**Rule:**
- Controllers (or view composers for cross-cutting/shared data) are responsible for fetching data. A Blade file should never call `Model::query()`, `DB::table()`, or any Eloquent method that hits the database.
- Conditionals and loops over already-provided collections are fine (`@if`, `@foreach`, `@can`); business logic and computed values that require more than a one-line transformation belong in a view model, presenter, or accessor — not inline `@php` blocks.
- Avoid `@php ... @endphp` blocks beyond trivial one-liners (e.g. `@php($count = count($items))`). If it needs more than that, it doesn't belong in the view.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Reusable UI w/ props/logic | `<x-...>` component |
| One-off template split, shared scope | `@include` |
| Structured/markup content | Slots |
| Configuration values | Props |
| New layouts | Blade layout components |
| Existing `@extends` codebases | Keep consistent, don't mix patterns |
| Data fetching | Controller / view composer only — never in Blade |