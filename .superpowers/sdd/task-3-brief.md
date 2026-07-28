### Task 3: Create the ministries Blade view

**Files:**
- Create: `resources/views/ministries.blade.php`

**Context from earlier tasks:**
- `Ministry` model has: `id`, `name`, `description` (TEXT), `ministry_category_id`, `timestamps`
- `MinistryCategory` model has: `id`, `name`, `timestamps`, `ministries()` relation
- `DemographicRestriction` has: `gender`, `age_min`, `age_max`, `marital_status`, `baptized` (boolean), `time_in_faith`
- `SkillRestriction` has: `music`, `technology`, `writing`, `technical`, `speaking`, `accounting`, `mentoring`, `bible_knowledge` (all booleans)
- `Skill` model has: `id`, `name`
- Controller passes: `$categories` (Collection of MinistryCategory with ministries), `$demographicRestrictions` (keyed by ministry_id), `$skillRestrictions` (keyed by ministry_id), `$skills` (Collection of Skill)
- This view extends `_layouts.master` which includes the topnav and footer
- The master layout has `@stack('head')` in `<head>` and `@stack('scripts')` before `</body>`
- The site uses Bootstrap 5, Tabler Icons (ti-* classes), and a purple (#8c52ff) design language

**Full view code to create:**

The file `resources/views/ministries.blade.php` should contain:

```blade
@extends('_layouts.master')

@section('title', 'Ministries — PERFIT')

@push('head')
<style>
    :root {
        --cat-core: #8c52ff;
        --cat-support: #2dce89;
        --cat-outreach: #fb6340;
        --cat-creative: #f5365c;
        --cat-care: #11cdef;
        --cat-special: #2dce89;
    }

    .ministry-hero {
        padding-top: 120px;
        padding-bottom: 60px;
        background: linear-gradient(135deg, #faf8ff 0%, #f0e6ff 50%, #faf8ff 100%);
        position: relative;
        overflow: hidden;
    }

    .ministry-section {
        padding: 60px 0;
        scroll-margin-top: 80px;
    }

    .ministry-section:nth-child(even) {
        background: linear-gradient(180deg, #faf8ff 0%, #f8f4ff 100%);
    }

    .category-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 32px;
    }

    .category-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .carousel-container {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(140,82,255,0.06);
        box-shadow: 0 4px 24px rgba(0,0,0,0.04);
    }

    .carousel-track {
        display: flex;
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .carousel-slide {
        flex: 0 0 100%;
        padding: 40px;
    }

    .carousel-slide h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 16px;
    }

    .carousel-slide .description {
        color: #4a3a6e;
        line-height: 1.8;
        margin-bottom: 24px;
        text-align: justify;
    }

    .requirements-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .requirements-table caption {
        font-weight: 700;
        color: #5a35b0;
        margin-bottom: 8px;
        text-align: left;
        font-size: 0.95rem;
    }

    .requirements-table th,
    .requirements-table td {
        border: 1px solid rgba(140,82,255,0.15);
        padding: 10px 12px;
        text-align: center;
    }

    .requirements-table th {
        background: rgba(140,82,255,0.06);
        color: #5a35b0;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .requirements-table td {
        color: #4a3a6e;
    }

    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 44px;
        height: 44px;
        border: 1px solid rgba(140,82,255,0.15);
        background: #fff;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 2;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .carousel-btn:hover {
        background: #8c52ff;
        box-shadow: 0 8px 25px rgba(140,82,255,0.3);
        transform: translateY(-50%) scale(1.1);
        border-color: #8c52ff;
    }

    .carousel-btn:hover i {
        color: #fff !important;
    }

    .carousel-btn-prev { left: 12px; }
    .carousel-btn-next { right: 12px; }

    .carousel-dots {
        display: flex;
        justify-content: center;
        gap: 8px;
        padding: 16px 0;
    }

    .carousel-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #d0c4e8;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        padding: 0;
    }

    .carousel-dot.active {
        background: #8c52ff;
        transform: scale(1.3);
    }

    .ministry-section .section-fade {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .ministry-section .section-fade.revealed {
        opacity: 1;
        transform: translateY(0);
    }

    @media (max-width: 768px) {
        .carousel-slide { padding: 24px 16px; }
        .carousel-slide h3 { font-size: 1.2rem; }
        .requirements-table { font-size: 0.75rem; }
        .requirements-table th,
        .requirements-table td { padding: 6px 4px; }
        .carousel-btn { width: 36px; height: 36px; }
    }
</style>
@endpush

@section('content')

<section class="ministry-hero text-center">
    <div class="container">
        <span class="d-inline-block px-3 py-1 rounded-pill fw-semibold text-uppercase mb-3" style="font-size: .75rem; letter-spacing: .1em; background: #ebdefb; color: #8c52ff;">
            Discover Your Calling
        </span>
        <h1 class="display-4 fw-bold mb-3">
            Explore <span style="color: #8c52ff;">Ministries</span>
        </h1>
        <p class="text-muted mx-auto" style="max-width: 560px; font-size: 1.1rem;">
            Find where your gifts and passion can make a difference in the church.
        </p>
    </div>
</section>

@php
$categoryColors = [
    1 => 'var(--cat-core)',
    2 => 'var(--cat-support)',
    3 => 'var(--cat-outreach)',
    4 => 'var(--cat-creative)',
    5 => 'var(--cat-care)',
    6 => 'var(--cat-special)',
];
@endphp

@foreach($categories as $catIndex => $category)
<section id="cat-{{ $category->id }}" class="ministry-section">
    <div class="container">
        <div class="section-fade">
            <div class="category-header">
                <div class="category-dot" style="background: {{ $categoryColors[$category->id] ?? '#8c52ff' }};"></div>
                <h2 class="fw-bold mb-0" style="color: #1a1a2e;">{{ $category->name }}</h2>
            </div>

            @php $catId = 'carousel-' . $category->id; @endphp

            <div class="carousel-container" id="{{ $catId }}">
                <div class="carousel-track">
                    @foreach($category->ministries as $ministry)
                        @php
                            $demo = $demographicRestrictions->get($ministry->id);
                            $skillRest = $skillRestrictions->get($ministry->id);

                            $ageDisplay = 'any';
                            if ($demo) {
                                if ($demo->age_min && $demo->age_max) {
                                    $ageDisplay = $demo->age_min . '-' . $demo->age_max;
                                } elseif ($demo->age_min) {
                                    $ageDisplay = $demo->age_min . '+';
                                }
                            }

                            $activeSkills = [];
                            if ($skillRest) {
                                foreach ($skills as $skill) {
                                    $col = \Illuminate\Support\Str::snake($skill->name);
                                    if ($skillRest->$col ?? false) {
                                        $activeSkills[] = $skill->name;
                                    }
                                }
                            }
                        @endphp

                        <div class="carousel-slide">
                            <h3>{{ $ministry->name }}</h3>
                            @if($ministry->description)
                                <p class="description">{{ $ministry->description }}</p>
                            @endif

                            @if($demo)
                            <table class="requirements-table">
                                <caption>Requirements to Join</caption>
                                <thead>
                                    <tr>
                                        <th>Gender</th>
                                        <th>Age</th>
                                        <th>Status</th>
                                        <th>Baptized</th>
                                        <th>Time in Faith</th>
                                        <th>Skills</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $demo->gender ? ucfirst($demo->gender) : 'any' }}</td>
                                        <td>{{ $ageDisplay }}</td>
                                        <td>{{ $demo->marital_status ? ucfirst($demo->marital_status) : 'any' }}</td>
                                        <td>{{ $demo->baptized ? 'Yes' : 'No' }}</td>
                                        <td>{{ $demo->time_in_faith ?? 'any' }}</td>
                                        <td>{{ !empty($activeSkills) ? implode(', ', $activeSkills) : 'None specified' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($category->ministries->count() > 1)
                <button class="carousel-btn carousel-btn-prev" type="button" aria-label="Previous">
                    <i class="ti ti-chevron-left" style="font-size: 1.3rem; color: #8c52ff;"></i>
                </button>
                <button class="carousel-btn carousel-btn-next" type="button" aria-label="Next">
                    <i class="ti ti-chevron-right" style="font-size: 1.3rem; color: #8c52ff;"></i>
                </button>
                <div class="carousel-dots"></div>
                @endif
            </div>
        </div>
    </div>
</section>
@endforeach

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.carousel-container').forEach(function (container) {
        const track = container.querySelector('.carousel-track');
        const slides = container.querySelectorAll('.carousel-slide');
        const prevBtn = container.querySelector('.carousel-btn-prev');
        const nextBtn = container.querySelector('.carousel-btn-next');
        const dotsContainer = container.querySelector('.carousel-dots');

        if (slides.length <= 1) return;

        let currentIndex = 0;

        slides.forEach(function (_, i) {
            const dot = document.createElement('button');
            dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
            dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
            dot.addEventListener('click', function () {
                goTo(i);
            });
            dotsContainer.appendChild(dot);
        });

        function goTo(index) {
            currentIndex = index;
            track.style.transform = 'translateX(-' + (index * 100) + '%)';
            dotsContainer.querySelectorAll('.carousel-dot').forEach(function (d, i) {
                d.classList.toggle('active', i === index);
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                goTo((currentIndex - 1 + slides.length) % slides.length);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                goTo((currentIndex + 1) % slides.length);
            });
        }
    });

    const fadeEls = document.querySelectorAll('.section-fade');
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    fadeEls.forEach(function (el) { observer.observe(el); });
</script>
@endpush
```

**Important implementation notes:**
- Do NOT add any comments
- The empty `resources/views/ministries.blade.php` already exists — overwrite it entirely
- The master layout already includes `@vite(['resources/css/app.css', 'resources/js/app.js'])` so no need to import CSS/JS separately
- Use `\Illuminate\Support\Str::snake()` in the Blade @php block (fully qualified because no `use` statement in Blade)
- The `categoryColors` array maps category ID 1-6 to CSS variables

**Verification:**
- Run `php artisan route:list` — no errors
- The `/ministries` page should load without Blade errors (it will look unstyled until Task 4 is complete, but should not error)
