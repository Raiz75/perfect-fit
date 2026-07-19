<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfit</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body { margin: 0 !important; padding: 0 !important; overflow-x: hidden !important; }
        .float-img { position: absolute; z-index: 1; animation: sway 6s ease-in-out infinite; object-fit: cover; border-radius: 1rem; box-shadow: 0 20px 60px rgba(0,0,0,0.4); }
        .float-img:nth-child(1) { width: 700px; height: 500px; top: 8%; right: 8%; animation-delay: 0s; }
        .float-img:nth-child(2) { width: 500px; height: 300px; top: 40%; right: 30%; animation-delay: -1.8s; }
        @keyframes sway { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-14px); } }

        .step-card { opacity: 0; transform: translateY(60px) scale(0.95); transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .step-card.revealed { opacity: 1; transform: translateY(0) scale(1); }
        .step-card:nth-child(1) { transition-delay: 0s; }
        .step-card:nth-child(2) { transition-delay: 0.15s; }
        .step-card:nth-child(3) { transition-delay: 0.3s; }
        .step-card:nth-child(4) { transition-delay: 0.45s; }
        .step-card:nth-child(5) { transition-delay: 0.6s; }
        .step-card:nth-child(6) { transition-delay: 0.75s; }

        .step-dot { animation: breathe 3s ease-in-out infinite; }
        @keyframes breathe {
            0%, 100% { box-shadow: 0 0 0 0 rgba(140, 82, 255, 0.3); transform: scale(1); }
            50% { box-shadow: 0 0 0 12px rgba(140, 82, 255, 0); transform: scale(1.08); }
        }

        #ministryCarousel .card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(140, 82, 255, 0.12) !important; }
        #ministryCarousel .carousel-control-prev:hover, #ministryCarousel .carousel-control-next:hover { background: #8c52ff !important; }
        #ministryCarousel .carousel-control-prev:hover svg, #ministryCarousel .carousel-control-next:hover svg { stroke: #fff; }
        .carousel-indicators [data-bs-target] { border-radius: 50% !important; }
        .carousel-indicators .active { background: #8c52ff !important; opacity: 1 !important; width: 24px !important; border-radius: 10px !important; }

        @media (max-width: 1023px) { .float-img { display: none !important; } .hero-content { margin-left: auto; margin-right: auto; text-align: center; align-items: center; } }
        @media (min-width: 1024px) { .hero-content { margin-left: 4.5rem; } }
        @media (min-width: 1280px) { .hero-content { margin-left: 8.5rem; } }
    </style>
</head>
<body>
    @include('_partials.topnav')

    <main>
        <section class="min-vh-100 d-flex flex-column flex-lg-row align-items-start overflow-hidden bg-cover bg-center position-relative" style="background-image: url('{{ asset('images/banner.png') }}'); padding-top: 120px;">
            <img src="{{ asset('images/bg.png') }}" alt="" class="float-img">
            <img src="{{ asset('images/bg.png') }}" alt="" class="float-img">

            <div class="hero-content d-flex flex-column align-items-center text-center text-lg-start" style="max-width: 560px; position: relative; z-index: 10;">
                <h1 class="display-1 fw-bold mb-3" style="color: #8c52ff;">
                    PERFIT
                </h1>
                <p class="text-muted mb-4" style="font-size: 1.125rem; max-width: 480px;">
                    PERFIT uses AI to match church volunteers with the right ministry roles, making service more meaningful and coordination effortless for leaders.
                </p>
                <a href="#" class="btn btn-lg rounded-pill text-white border-0 px-5" style="background: #8c52ff;">
                    Take Assessment
                </a>
            </div>
        </section>

        <section class="py-5 position-relative overflow-hidden" style="background: #faf8ff;">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: radial-gradient(circle, #8c52ff08 1px, transparent 1px); background-size: 24px 24px; pointer-events: none;"></div>
            <div class="position-absolute" style="width: 500px; height: 500px; border-radius: 50%; background: radial-gradient(circle, #8c52ff08 0%, transparent 70%); top: -200px; right: -150px; pointer-events: none;"></div>
            <div class="position-absolute" style="width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle, #e2cffa15 0%, transparent 70%); bottom: -150px; left: -100px; pointer-events: none;"></div>
            <div class="container position-relative">
                <div class="text-center mb-5">
                    <h2 class="display-5 fw-bold mb-3">How It <span style="color: #8c52ff;">Works</span></h2>
                    <p class="text-muted mx-auto" style="max-width: 540px;">Follow these simple steps to complete your assessment and discover your fit.</p>
                </div>

                @php $steps = [
                    ['num' => '01', 'title' => 'Choose User Type', 'desc' => 'Select Leader to set restrictions or Volunteer to take the assessment.', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ['num' => '02', 'title' => 'Enter Church Code', 'desc' => 'Use your church\'s code to apply your pastor\'s settings.', 'icon' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M15 14a3.001 3.001 0 012.83 2'],
                    ['num' => '03', 'title' => 'Choose Language', 'desc' => 'Choose your preferred language.', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['num' => '04', 'title' => 'Answer the Assessment', 'desc' => 'Carefully go through each question and select your answer.', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['num' => '05', 'title' => 'Submit the Assessment', 'desc' => 'Once done, Submit your response for evaluation.', 'icon' => 'M5 13l4 4L19 7'],
                    ['num' => '06', 'title' => 'See the Result', 'desc' => 'After submission, check your personalized result.', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                ]; @endphp

                <div class="position-relative">
                    <div class="position-absolute start-50 top-0 bottom-0 d-none d-md-block" style="width: 3px; background: linear-gradient(180deg, #8c52ff15, #8c52ff40, #8c52ff, #8c52ff40, #8c52ff15); translate: -50%;"></div>

                    @foreach($steps as $i => $step)
                    @php $side = $i % 2 === 0 ? 'left' : 'right'; @endphp

                    @if($side === 'left')
                    <div class="row g-0 mb-5 step-card align-items-center">
                        <div class="col-md-5 text-md-end">
                            <div class="p-3 p-md-4">
                                <span class="badge fw-bold border-0 rounded-pill px-3 py-1.5 mb-2" style="background: linear-gradient(135deg, #8c52ff, #a978ff); color: #fff; font-size: .65rem; letter-spacing: .08em;">STEP {{ $step['num'] }}</span>
                                <h4 class="fw-bold mb-2" style="color: #1a1a2e;">{{ $step['title'] }}</h4>
                                <p class="text-muted mb-0" style="max-width: 380px; margin-left: auto; line-height: 1.7;">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                        <div class="col-md-2 d-none d-md-flex justify-content-center position-relative z-1">
                            <div class="step-dot d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 52px; height: 52px; background: #fff; border: 3px solid #8c52ff; box-shadow: 0 0 0 8px rgba(140, 82, 255, 0.08);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#8c52ff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="{{ $step['icon'] }}" />
                                </svg>
                            </div>
                        </div>
                        <div class="col-md-5 d-none d-md-block"></div>
                    </div>
                    @else
                    <div class="row g-0 mb-5 step-card align-items-center">
                        <div class="col-md-5 d-none d-md-block"></div>
                        <div class="col-md-2 d-none d-md-flex justify-content-center position-relative z-1">
                            <div class="step-dot d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 52px; height: 52px; background: #fff; border: 3px solid #8c52ff; box-shadow: 0 0 0 8px rgba(140, 82, 255, 0.08);">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#8c52ff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="{{ $step['icon'] }}" />
                                </svg>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="p-3 p-md-4">
                                <span class="badge fw-bold border-0 rounded-pill px-3 py-1.5 mb-2" style="background: linear-gradient(135deg, #8c52ff, #a978ff); color: #fff; font-size: .65rem; letter-spacing: .08em;">STEP {{ $step['num'] }}</span>
                                <h4 class="fw-bold mb-2" style="color: #1a1a2e;">{{ $step['title'] }}</h4>
                                <p class="text-muted mb-0" style="max-width: 380px; line-height: 1.7;">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-5 position-relative overflow-hidden" style="background: linear-gradient(180deg, #fff 0%, #f8f4ff 100%);">
            <div class="position-absolute top-0 end-0 w-50 h-50 opacity-25" style="background: radial-gradient(circle, #8c52ff20 0%, transparent 70%); pointer-events: none;"></div>
            <div class="position-absolute bottom-0 start-0 w-50 h-50 opacity-40" style="background: radial-gradient(circle, #e2cffa 0%, transparent 70%); pointer-events: none;"></div>
            <div class="container position-relative">
                <div class="text-center mb-5">
                    <span class="d-inline-block px-3 py-1 rounded-pill fw-semibold text-uppercase mb-3" style="font-size: .75rem; letter-spacing: .1em; background: #ebdefb; color: #8c52ff;">Discover Your Calling</span>
                    <h2 class="display-5 fw-bold mb-3">Explore <span style="color: #8c52ff;">Ministries</span></h2>
                    <p class="text-muted mx-auto" style="max-width: 560px;">Find where your gifts and passion can make a difference in the church.</p>
                </div>

                @php
                    $ministries = [
                        ['name' => 'Worship — Singing', 'category' => 'Core', 'desc' => 'You love to sing and you want to use your voice to help people connect with God during service.', 'color' => '#8c52ff'],
                        ['name' => 'Worship — Dancing', 'category' => 'Core', 'desc' => 'For those who feel closest to God when moving — dance as an offering, not a performance.', 'color' => '#8c52ff'],
                        ['name' => 'Worship — Instrument', 'category' => 'Core', 'desc' => 'Guitar, piano, drums, or whatever you play — if music is how you worship, there\'s a place for you here.', 'color' => '#8c52ff'],
                        ['name' => 'Prayer', 'category' => 'Core', 'desc' => 'Not everyone is loud about it, but if you carry people in your heart and talk to God for them, this is your tribe.', 'color' => '#8c52ff'],
                        ['name' => 'Preaching', 'category' => 'Core', 'desc' => 'You have a gift for breaking down the Word in a way that lands in people\'s hearts, not just their heads.', 'color' => '#8c52ff'],
                        ['name' => 'Discipleship', 'category' => 'Core', 'desc' => 'Walking with someone as they grow in faith — not teaching at them, but walking beside them.', 'color' => '#8c52ff'],
                        ['name' => 'Youth', 'category' => 'Core', 'desc' => 'The next generation needs adults who actually listen, who show up, and who make faith feel real and relevant.', 'color' => '#8c52ff'],
                        ['name' => 'Young Adults', 'category' => 'Core', 'desc' => 'Navigating life after school — career, relationships, purpose. This is a space to figure it out together.', 'color' => '#8c52ff'],
                        ['name' => 'Men\'s', 'category' => 'Core', 'desc' => 'Real talk about faith, family, and leading well. No masks, just brothers sharpening brothers.', 'color' => '#8c52ff'],
                        ['name' => 'Women\'s', 'category' => 'Core', 'desc' => 'A safe place for women to grow, share struggles, and encourage each other in every season of life.', 'color' => '#8c52ff'],
                        ['name' => 'Family & Couples', 'category' => 'Core', 'desc' => 'Marriage is beautiful and hard. This ministry helps couples build homes rooted in grace and truth.', 'color' => '#8c52ff'],
                        ['name' => 'Ushering', 'category' => 'Support', 'desc' => 'You\'re often the first face people see when they walk in. A warm smile and a helpful hand go a long way.', 'color' => '#2dce89'],
                        ['name' => 'Administration', 'category' => 'Support', 'desc' => 'Behind every smooth-running church is someone who loves organization, spreadsheets, and making things work.', 'color' => '#2dce89'],
                        ['name' => 'Finance', 'category' => 'Support', 'desc' => 'Stewardship matters. If you have a head for numbers and a heart for integrity, we need you.', 'color' => '#2dce89'],
                        ['name' => 'Marshal', 'category' => 'Support', 'desc' => 'Keeping things safe and orderly so everyone can focus on worship without worry.', 'color' => '#2dce89'],
                        ['name' => 'Facilities', 'category' => 'Support', 'desc' => 'Someone has to fix the leaking roof, set up chairs, and make sure the AC works. That someone matters.', 'color' => '#2dce89'],
                        ['name' => 'Evangelism', 'category' => 'Outreach', 'desc' => 'You can\'t help telling people about Jesus. Not in a pushy way — just because the good news is too good to keep.', 'color' => '#fb6340'],
                        ['name' => 'Missions', 'category' => 'Outreach', 'desc' => 'Whether across the street or across the world, you\'re ready to go where the Gospel hasn\'t reached yet.', 'color' => '#fb6340'],
                        ['name' => 'Community Service', 'category' => 'Outreach', 'desc' => 'Faith without works is dead. You show God\'s love through feeding, clothing, and serving the community.', 'color' => '#fb6340'],
                        ['name' => 'Visitation', 'category' => 'Outreach', 'desc' => 'The elderly, the sick, the shut-ins — you visit them not out of duty, but because you genuinely care.', 'color' => '#fb6340'],
                        ['name' => 'Production Tech', 'category' => 'Creative', 'desc' => 'Sound, lights, slides — you make sure the technical side disappears so people can focus on encountering God.', 'color' => '#f5365c'],
                        ['name' => 'Creative & Media', 'category' => 'Creative', 'desc' => 'You tell stories through design, video, and social media. You help the church\'s voice reach beyond its walls.', 'color' => '#f5365c'],
                        ['name' => 'Counseling', 'category' => 'Care', 'desc' => 'People come to you with their heaviest burdens. You listen, you pray, and you point them to hope.', 'color' => '#11cdef'],
                        ['name' => 'Healing & Deliverance', 'category' => 'Care', 'desc' => 'You believe God still heals and sets people free. You create space for the Holy Spirit to move.', 'color' => '#11cdef'],
                        ['name' => 'Funeral', 'category' => 'Care', 'desc' => 'In the hardest moments of loss, you help families say goodbye with dignity, faith, and hope.', 'color' => '#11cdef'],
                        ['name' => 'Addiction Recovery', 'category' => 'Care', 'desc' => 'You walk with people through the long, hard road to freedom. You don\'t give up on them.', 'color' => '#11cdef'],
                        ['name' => 'Special Needs', 'category' => 'Care', 'desc' => 'Every person matters. You create a space where individuals with special needs are loved, included, and celebrated.', 'color' => '#11cdef'],
                        ['name' => 'Seniors', 'category' => 'Fellowship', 'desc' => 'The titos and titas of the church have decades of wisdom and prayer. They are the backbone of every congregation.', 'color' => '#2dce89'],
                        ['name' => 'Single Adults', 'category' => 'Fellowship', 'desc' => 'Not waiting around — living fully. This is a community for singles who want to grow, serve, and thrive.', 'color' => '#2dce89'],
                    ];

                    $chunks = array_chunk($ministries, 3);
                @endphp

                <div id="ministryCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                    <div class="carousel-indicators position-static mb-4">
                        @foreach($chunks as $i => $chunk)
                        <button type="button" data-bs-target="#ministryCarousel" data-bs-slide-to="{{ $i }}" class="border-0 rounded-pill" style="width: 10px; height: 10px; background: #d0c4e8; {{ $i === 0 ? 'opacity: 1;' : '' }}" aria-label="Slide {{ $i + 1 }}"></button>
                        @endforeach
                    </div>

                    <div class="carousel-inner">
                        @foreach($chunks as $i => $chunk)
                        <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                            <div class="row g-4">
                                @foreach($chunk as $m)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px;">
                                        <div style="height: 4px; background: {{ $m['color'] }}; border-radius: 16px 16px 0 0;"></div>
                                        <div class="card-body d-flex flex-column p-4">
                                            <h5 class="card-title fw-bold mb-2">{{ $m['name'] }}</h5>
                                            <p class="card-text text-muted small flex-grow-1">{{ $m['desc'] }}</p>
                                            <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                                                <span class="badge rounded-pill fw-normal" style="background: {{ $m['color'] }}20; color: {{ $m['color'] }}; font-size: .7rem;">{{ $m['category'] }}</span>
                                                <a href="#" class="btn btn-sm" style="color: {{ $m['color'] }};">Learn More</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#ministryCarousel" data-bs-slide="prev" style="width: 44px; height: 44px; top: 50%; transform: translateY(-50%); background: #fff; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,.12); left: 0; opacity: 1; border: 1px solid #eee;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8c52ff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#ministryCarousel" data-bs-slide="next" style="width: 44px; height: 44px; top: 50%; transform: translateY(-50%); background: #fff; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,.12); right: 0; opacity: 1; border: 1px solid #eee;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8c52ff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </div>
        </section>
    </main>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.step-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.2, rootMargin: '0px 0px -50px 0px' });
            cards.forEach(card => observer.observe(card));

            const carousel = document.getElementById('ministryCarousel');
            if (carousel && typeof bootstrap !== 'undefined') {
                new bootstrap.Carousel(carousel, { interval: 5000 });
            }
        });
    </script>
</body>
</html>
