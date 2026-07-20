@extends('_layouts.master')

@section('title', 'PERFIT')

@push('head')
<style>
    .float-img { position: absolute; z-index: 1; animation: sway 6s ease-in-out infinite; object-fit: cover; border-radius: 1rem; box-shadow: 0 20px 60px rgba(0,0,0,0.4); }
    .float-img:nth-child(1) { width: 700px; height: 500px; top: 12%; right: 8%; animation-delay: 0s; }
    .float-img:nth-child(2) { width: 500px; height: 300px; top: 42%; right: 30%; animation-delay: -1.8s; }
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

    .modal-overlay {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5); backdrop-filter: blur(6px); z-index: 100;
        justify-content: center; align-items: center;
    }
    .modal-overlay.show { display: flex; }
    .modal-box {
        background: white; padding: 2.5rem; border-radius: 20px; text-align: center;
        width: 90%; max-width: 400px; box-shadow: 0 20px 60px rgba(0,0,0,0.25); position: relative;
    }
    .modal-box h3 { color: #8c52ff; font-weight: 700; margin-bottom: 0.75rem; }
    .modal-box p { color: #666; font-size: 0.95rem; margin-bottom: 1.5rem; }
    .modal-btn {
        display: block; width: 80%; margin: 0.75rem auto; padding: 12px;
    }
    .modal-close {
        position: absolute; top: 12px; right: 16px; border: none; background: none;
        font-size: 1.5rem; color: #999; cursor: pointer; line-height: 1;
    }
    .modal-close:hover { color: #333; }
    .modal-input {
        width: 90%; padding: 12px; border: 2px solid #8c52ff; border-radius: 10px;
        font-size: 1.3rem; text-align: center; letter-spacing: 4px; font-weight: bold;
        color: #8c52ff; margin: 0.5rem auto 1rem; display: block; outline: none;
    }
    .privacy-row { font-size: 0.8rem; color: #999; margin-top: 1rem; display: flex; align-items: center; justify-content: center; gap: 6px; }
    .privacy-row a { color: #8c52ff; cursor: pointer; text-decoration: underline; }
    .dove-trigger {
        position: fixed; bottom: 24px; left: 24px; z-index: 50;
        background: none; border: none; cursor: pointer; text-align: center; width: 100px;
    }
    .dove-trigger img { width: 60px; transition: transform 0.3s; }
    .dove-trigger img:hover { transform: scale(1.15); }
    .dove-trigger p { font-size: 0.7rem; color: #aaa; margin: 4px 0 0; }
    .verse-overlay.show { display: flex; }
    .verse-dove { width: 80px; margin: 0 auto; display: none; }
    .verse-box { opacity: 0; transition: opacity 1s; margin-top: 1rem; }
    .verse-box.show { opacity: 1; }
    .verse-box h4 { color: #8c52ff; margin-bottom: 0.5rem; }
    .verse-box p { font-style: italic; line-height: 1.6; }

    @media (max-width: 1023px) { .float-img { display: none !important; } .hero-content { margin-left: auto; margin-right: auto; text-align: center; align-items: center; } }
    @media (min-width: 1024px) { .hero-content { margin-left: 4.5rem; } }
    @media (min-width: 1280px) { .hero-content { margin-left: 8.5rem; } }
</style>
@endpush

@section('content')
    <section id="home" class="min-vh-100 bg-cover d-flex align-items-center" style="background-image: url('{{ asset('images/banner.png') }}');">
        <img src="{{ asset('images/bg.png') }}" alt="" class="float-img">
        <img src="{{ asset('images/bg.png') }}" alt="" class="float-img">

        <div class="hero-content text-center" style="max-width: 560px; position: relative; z-index: 10; margin-bottom: 80px;">
            <h1 class="display-1 fw-bold mb-3" style="color: #8c52ff;">
                PERFIT
            </h1>
            <p class="text-muted mb-4" style="font-size: 1.125rem; max-width: 480px;">
                PERFIT uses AI to match church volunteers with the right ministry roles, making service more meaningful and coordination effortless for leaders.
            </p>
            <button class="btn btn-lg primary-btn-perfit" onclick="openModal('overlayUser')">
                Take Assessment
            </button>
        </div>
    </section>
    <section id="how-it-works" class="py-5 position-relative" style="background: #faf8ff;">
        <div class="container position-relative">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">How It <span style="color: #8c52ff;">Works</span></h2>
                <p class="text-muted mx-auto" style="max-width: 540px;">Follow these simple steps to complete your assessment and discover your fit.</p>
            </div>

            @php $steps = [
                ['num' => '01', 'title' => 'Choose User Type', 'desc' => 'Select Leader to set restrictions or Volunteer to take the assessment.', 'icon' => 'M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0 M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2 M16 3.13a4 4 0 0 1 0 7.75 M21 21v-2a4 4 0 0 0 -3 -3.85'],
                ['num' => '02', 'title' => 'Enter Church Code', 'desc' => 'Use your church\'s code to apply your pastor\'s settings.', 'icon' => 'M3 21l18 0 M10 21v-4a2 2 0 0 1 4 0v4 M10 5l4 0 M12 3l0 5 M6 21v-7m-2 2l8 -8l8 8m-2 -2v7'],
                ['num' => '03', 'title' => 'Choose Language', 'desc' => 'Choose your preferred language.', 'icon' => 'M4 5h7 M9 3v2c0 4.418 -2.239 8 -5 8 M5 9c0 2.144 2.952 3.908 6.7 4 M12 20l4 -9l4 9 M19.1 18h-6.2'],
                ['num' => '04', 'title' => 'Answer the Assessment', 'desc' => 'Carefully go through each question and select your answer.', 'icon' => 'M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2 M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z M9 12l.01 0 M13 12l2 0 M9 16l.01 0 M13 16l2 0'],
                ['num' => '05', 'title' => 'Submit the Assessment', 'desc' => 'Once done, Submit your response for evaluation.', 'icon' => 'M10 14l11 -11 M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5'],
                ['num' => '06', 'title' => 'See the Result', 'desc' => 'After submission, check your personalized result.', 'icon' => 'M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z M4 20l14 0'],
            ]; @endphp

            <div class="position-relative">
                <div class="position-absolute start-50 top-0 bottom-0 d-none d-md-block" style="width: 3px; background: linear-gradient(180deg, #8c52ff15, #8c52ff40, #8c52ff, #8c52ff40, #8c52ff15); translate: -50%;"></div>

                @foreach($steps as $i => $step)
                @php $side = $i % 2 === 0 ? 'left' : 'right'; @endphp

                @if($side === 'left')
                <div class="row g-0 mb-5 step-card align-items-center">
                    <div class="col-md-5 text-md-end">
                        <div class="p-3 p-md-4">
                            <span class="badge fw-bold border-0 rounded-pill px-3 py-1.5 mb-2 p-3" style="background: linear-gradient(135deg, #8c52ff, #a978ff); color: #fff; font-size: .65rem; letter-spacing: .08em;">STEP {{ $step['num'] }}</span>
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
                            <span class="badge fw-bold border-0 rounded-pill px-3 py-1.5 mb-2 p-3" style="background: linear-gradient(135deg, #8c52ff, #a978ff); color: #fff; font-size: .65rem; letter-spacing: .08em;">STEP {{ $step['num'] }}</span>
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

    <section id="ministries" class="py-5 position-relative" style="background: linear-gradient(180deg, #fff 0%, #f8f4ff 100%);">
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

    <div class="modal-overlay" id="overlayUser">
        <div class="modal-box">
            <button class="modal-close" onclick="closeModal('overlayUser')">&times;</button>
            <h3>Choose User Type</h3>
            <button class="modal-btn primary-btn-perfit" onclick="setUser('leader')">Leader</button>
            <button class="modal-btn primary-btn-perfit" onclick="setUser('volunteer')">Volunteer</button>
        </div>
    </div>

    <div class="modal-overlay" id="overlayChurch">
        <div class="modal-box">
            <button class="modal-close" onclick="closeModal('overlayChurch')">&times;</button>
            <h3>Enter Church Code</h3>
            <p>Enter your church code to apply your pastor's settings.</p>
            <input type="text" class="modal-input" id="inputedChurchCode" placeholder="Code" maxlength="9">
            <button class="modal-btn primary-btn-perfit" onclick="selectLang()">Next</button>
        </div>
    </div>

    <div class="modal-overlay" id="overlayLang">
        <div class="modal-box">
            <button class="modal-close" onclick="closeModal('overlayLang')">&times;</button>
            <h3>Choose Language</h3>
            <button class="modal-btn primary-btn-perfit" onclick="setLang('en')">English</button>
            <button class="modal-btn primary-btn-perfit" onclick="setLang('tl')">Tagalog</button>
            <div class="privacy-row">
                <input type="checkbox" id="privacyPolicy">
                <label for="privacyPolicy">I accept the <a href="{{ route('privacy-policy') }}">Privacy Policy</a></label>
            </div>
        </div>
    </div>

    <div class="modal-overlay verse-overlay" id="doveOverlay">
        <div class="modal-box">
            <img src="{{ asset('images/doveStatic.png') }}" class="verse-dove" id="dynamicDove" style="display:block;">
            <div class="verse-box show" id="verseDiv">
                <h4 id="verseTitle">Title 1:1</h4>
                <p id="verse">Verse message</p>
                <button class="modal-btn primary-btn-perfit" onclick="verseDone()">Back</button>
            </div>
        </div>
    </div>

    <button class="dove-trigger" onclick="generateRandomVerse()" id="randomVerse">
        <img src="{{ asset('images/doveStatic.png') }}" alt="Dove">
        <p id="randomVerseP">Tap the dove for a verse</p>
    </button>
@endsection

@push('scripts')
<script>
    const verses = [
        {title:"1 Corinthians 12:4-5",text:"There are different kinds of gifts, but the same Spirit distributes them. There are different kinds of service, but the same Lord."},
        {title:"Romans 12:6",text:"We have different gifts, according to the grace given to each of us. If your gift is prophesying, then prophesy in accordance with your faith."},
        {title:"Ephesians 2:10",text:"For we are God's handiwork, created in Christ Jesus to do good works, which God prepared in advance for us to do."},
        {title:"1 Peter 4:10",text:"Each of you should use whatever gift you have received to serve others, as faithful stewards of God's grace in its various forms."},
        {title:"Jeremiah 1:5",text:"Before I formed you in the womb I knew you, before you were born I set you apart; I appointed you as a prophet to the nations."},
        {title:"Colossians 3:23",text:"Whatever you do, work at it with all your heart, as working for the Lord, not for human masters."},
        {title:"Proverbs 16:9",text:"In their hearts humans plan their course, but the Lord establishes their steps."},
        {title:"Isaiah 6:8",text:"Then I heard the voice of the Lord saying, 'Whom shall I send? And who will go for us?' And I said, 'Here am I. Send me!'"},
        {title:"Matthew 5:16",text:"Let your light shine before others, that they may see your good deeds and glorify your Father in heaven."},
        {title:"Philippians 2:13",text:"For it is God who works in you to will and to act in order to fulfill his good purpose."},
        {title:"1 Corinthians 12:27",text:"Now you are the body of Christ, and each one of you is a part of it."},
        {title:"Galatians 6:9",text:"Let us not become weary in doing good, for at the proper time we will reap a harvest if we do not give up."},
        {title:"John 15:16",text:"You did not choose me, but I chose you and appointed you so that you might go and bear fruit—fruit that will last."},
        {title:"Hebrews 13:20-21",text:"Now may the God of peace equip you with everything good for doing his will, and may he work in us what is pleasing to him."},
        {title:"2 Timothy 1:6",text:"Fan into flame the gift of God, which is in you."},
        {title:"Joshua 1:9",text:"Be strong and courageous. Do not be afraid; do not be discouraged, for the Lord your God will be with you wherever you go."},
    ];

    function openModal(id) { document.getElementById(id).classList.add('show'); }
    function closeModal(id) { document.getElementById(id).classList.remove('show'); }

    function setUser(type) {
        if (type === 'leader') {
            closeModal('overlayUser');
            window.location.href = '/admin/login';
        } else if (type === 'volunteer') {
            closeModal('overlayUser');
            openModal('overlayChurch');
        }
    }

    function selectLang() {
        const code = document.getElementById('inputedChurchCode').value.trim();
        if (!code) {
            alert('Please enter a church code.');
            return;
        }
        fetch('/admin/validate-church-code', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ church_code: code })
        })
        .then(r => r.json())
        .then(data => {
            if (data.exists) {
                localStorage.setItem('churchCode', code);
                closeModal('overlayChurch');
                openModal('overlayLang');
                document.getElementById('inputedChurchCode').value = '';
            } else {
                alert('Invalid church code.');
            }
        })
        .catch(function() { alert('Error validating church code.'); });
    }

    function setLang(lang) {
        if (!document.getElementById('privacyPolicy').checked) {
            document.querySelector('.privacy-row').style.color = 'red';
            setTimeout(function() { document.querySelector('.privacy-row').style.color = '#999'; }, 2000);
            return;
        }
        closeModal('overlayLang');
        localStorage.setItem('selectedLanguage', lang);
        window.location.href = '/assessment';
    }

    function generateRandomVerse() {
        const idx = Math.floor(Math.random() * verses.length);
        document.getElementById('verseTitle').textContent = verses[idx].title;
        document.getElementById('verse').textContent = verses[idx].text;
        openModal('doveOverlay');
    }

    function verseDone() { closeModal('doveOverlay'); }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('a[href^="#"]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const el = document.querySelector(href);
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        const toggler = document.getElementById('navbarToggler');
        const menu = document.getElementById('navbarMenu');
        if (toggler && menu) {
            toggler.addEventListener('click', function () {
                const isOpen = menu.classList.toggle('show');
                this.setAttribute('aria-expanded', isOpen);
                this.classList.toggle('collapsed', !isOpen);
            });
            document.querySelectorAll('.nav-scroll').forEach(function (link) {
                link.addEventListener('click', function () {
                    menu.classList.remove('show');
                    toggler.classList.add('collapsed');
                    toggler.setAttribute('aria-expanded', 'false');
                });
            });
        }

        const cards = document.querySelectorAll('.step-card');
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) { entry.target.classList.add('revealed'); obs.unobserve(entry.target); }
            });
        }, { threshold: 0.2, rootMargin: '0px 0px -50px 0px' });
        cards.forEach(card => obs.observe(card));

        (function () {
            const c = document.getElementById('ministryCarousel');
            if (!c) return;
            const items = c.querySelectorAll('.carousel-item');
            const dots = c.querySelectorAll('.carousel-indicators button');
            let cur = 0, timer = null;
            function go(i) { items.forEach((el,idx) => el.classList.toggle('active', idx===i)); dots.forEach((el,idx) => { el.classList.toggle('active', idx===i); el.style.opacity = idx===i ? '1' : ''; el.style.background = idx===i ? '#8c52ff' : '#d0c4e8'; }); cur = i; }
            function n() { go((cur+1)%items.length); }
            function p() { go((cur-1+items.length)%items.length); }
            function rt() { if(timer) clearInterval(timer); timer = setInterval(n, 5000); }
            c.querySelector('.carousel-control-next').addEventListener('click', function(e) { e.preventDefault(); n(); rt(); });
            c.querySelector('.carousel-control-prev').addEventListener('click', function(e) { e.preventDefault(); p(); rt(); });
            dots.forEach(function(btn, idx) { btn.addEventListener('click', function() { go(idx); rt(); }); });
            rt();
        })();
    });
</script>
@endpush
