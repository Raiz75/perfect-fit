@extends('_layouts.master')

@section('title', 'PERFIT')

@push('head')
<style>
    :root { --purple: #8c52ff; --purple-glow: rgba(140, 82, 255, 0.3); }

    .bg-hero {
        background-size: 400% 400%;
        animation: heroShift 16s ease infinite;
    }
    @keyframes heroShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .hero-glow {
        position: absolute; border-radius: 50%; pointer-events: none;
    }
    .hero-glow:nth-child(1) {
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(140,82,255,0.28) 0%, rgba(120,60,230,0.08) 60%, transparent);
        top: -15%; left: -10%;
        animation: glowDrift 14s ease-in-out infinite;
        filter: blur(40px);
    }
    .hero-glow:nth-child(2) {
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(100,180,255,0.25) 0%, rgba(60,130,220,0.08) 60%, transparent);
        top: -15%; right: -10%;
        animation: glowDrift 14s ease-in-out infinite reverse;
        filter: blur(40px);
    }
    .hero-glow:nth-child(3) {
        width: 450px; height: 450px;
        background: radial-gradient(circle, rgba(200,120,255,0.22) 0%, rgba(140,82,255,0.1) 60%, transparent);
        bottom: -10%; left: 50%;
        transform: translateX(-50%);
        animation: glowDrift 18s ease-in-out infinite;
        filter: blur(50px);
    }
    .hero-glow:nth-child(4) {
        width: 350px; height: 350px;
        background: radial-gradient(circle, rgba(255,180,80,0.15) 0%, rgba(255,140,60,0.06) 60%, transparent);
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        animation: glowPulse 5s ease-in-out infinite;
        filter: blur(60px);
    }
    @keyframes glowDrift {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(40px, -40px) scale(1.1); }
        66% { transform: translate(-40px, 30px) scale(0.9); }
    }
    @keyframes glowPulse {
        0%, 100% { transform: translate(-50%, -50%) scale(0.8); opacity: 0.3; }
        50% { transform: translate(-50%, -50%) scale(1.5); opacity: 0.9; }
    }

    .hero-ring {
        position: absolute; border-radius: 50%; pointer-events: none;
        animation: ringDrift 12s ease-in-out infinite;
    }
    .hero-ring:nth-child(5) {
        width: 320px; height: 320px;
        top: 12%; left: 3%;
        border: 2.5px solid rgba(140,82,255,0.3);
        box-shadow: 0 0 20px rgba(140,82,255,0.1), inset 0 0 20px rgba(140,82,255,0.05);
        animation-delay: 0s;
    }
    .hero-ring:nth-child(6) {
        width: 320px; height: 320px;
        top: 12%; right: 3%;
        border: 2.5px solid rgba(100,180,255,0.3);
        box-shadow: 0 0 20px rgba(100,180,255,0.1), inset 0 0 20px rgba(100,180,255,0.05);
        animation-delay: 0.5s;
    }
    .hero-ring:nth-child(7) {
        width: 220px; height: 220px;
        bottom: 14%; left: 7%;
        border: 2px solid rgba(200,120,255,0.25);
        box-shadow: 0 0 15px rgba(200,120,255,0.08);
        animation-delay: 1s;
    }
    .hero-ring:nth-child(8) {
        width: 220px; height: 220px;
        bottom: 14%; right: 7%;
        border: 2px solid rgba(200,120,255,0.25);
        box-shadow: 0 0 15px rgba(200,120,255,0.08);
        animation-delay: 1.5s;
    }
    @keyframes ringDrift {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        25% { transform: translate(20px, -15px) rotate(6deg); }
        50% { transform: translate(-15px, 20px) rotate(-4deg); }
        75% { transform: translate(15px, -8px) rotate(5deg); }
    }

    .hero-particle {
        position: absolute; border-radius: 50%; pointer-events: none;
        background: rgba(140,82,255,0.5);
        animation: particleFloat var(--dur, 8s) ease-in-out infinite;
        box-shadow: 0 0 6px rgba(140,82,255,0.3);
    }
    .hero-particle:nth-child(9) { top: 8%; left: 5%; width: 7px; height: 7px; --dur: 6s; animation-delay: 0s; }
    .hero-particle:nth-child(10) { top: 8%; right: 5%; width: 7px; height: 7px; --dur: 6s; animation-delay: 0.3s; background: rgba(100,180,255,0.6); box-shadow: 0 0 6px rgba(100,180,255,0.3); }
    .hero-particle:nth-child(11) { top: 20%; left: 1.5%; width: 10px; height: 10px; --dur: 8s; animation-delay: 0.6s; background: rgba(255,180,80,0.5); box-shadow: 0 0 8px rgba(255,180,80,0.3); }
    .hero-particle:nth-child(12) { top: 20%; right: 1.5%; width: 10px; height: 10px; --dur: 8s; animation-delay: 0.9s; background: rgba(255,180,80,0.5); box-shadow: 0 0 8px rgba(255,180,80,0.3); }
    .hero-particle:nth-child(13) { top: 34%; left: 4%; width: 6px; height: 6px; --dur: 5.5s; animation-delay: 1.2s; opacity: 0.6; }
    .hero-particle:nth-child(14) { top: 34%; right: 4%; width: 6px; height: 6px; --dur: 5.5s; animation-delay: 1.5s; opacity: 0.6; }
    .hero-particle:nth-child(15) { top: 48%; left: 1%; width: 12px; height: 12px; --dur: 9s; animation-delay: 1.8s; opacity: 0.35; background: rgba(100,180,255,0.4); box-shadow: 0 0 10px rgba(100,180,255,0.2); }
    .hero-particle:nth-child(16) { top: 48%; right: 1%; width: 12px; height: 12px; --dur: 9s; animation-delay: 2.1s; opacity: 0.35; background: rgba(100,180,255,0.4); box-shadow: 0 0 10px rgba(100,180,255,0.2); }
    .hero-particle:nth-child(17) { top: 62%; left: 3%; width: 7px; height: 7px; --dur: 7s; animation-delay: 2.4s; }
    .hero-particle:nth-child(18) { top: 62%; right: 3%; width: 7px; height: 7px; --dur: 7s; animation-delay: 2.7s; background: rgba(200,120,255,0.6); box-shadow: 0 0 6px rgba(200,120,255,0.3); }
    .hero-particle:nth-child(19) { bottom: 10%; left: 5%; width: 8px; height: 8px; --dur: 6.5s; animation-delay: 3s; background: rgba(255,180,80,0.45); box-shadow: 0 0 8px rgba(255,180,80,0.25); }
    .hero-particle:nth-child(20) { bottom: 10%; right: 5%; width: 8px; height: 8px; --dur: 6.5s; animation-delay: 3.3s; background: rgba(255,180,80,0.45); box-shadow: 0 0 8px rgba(255,180,80,0.25); }
    .hero-particle:nth-child(21) { bottom: 3%; left: 10%; width: 5px; height: 5px; --dur: 5.5s; animation-delay: 3.6s; opacity: 0.4; }
    .hero-particle:nth-child(22) { bottom: 3%; right: 10%; width: 5px; height: 5px; --dur: 5.5s; animation-delay: 3.9s; opacity: 0.4; }
    @keyframes particleFloat {
        0%, 100% { transform: translateY(0) scale(1); opacity: 0.4; }
        50% { transform: translateY(-30px) scale(1.6); opacity: 0.9; }
    }

    .hero-shard {
        position: absolute; pointer-events: none;
        width: 4px; height: 20px;
        background: linear-gradient(180deg, rgba(140,82,255,0.5), transparent);
        border-radius: 2px;
        animation: shardFall 8s linear infinite;
    }
    .hero-shard:nth-child(23) { left: 8%; top: -20px; animation-delay: 0s; height: 18px; }
    .hero-shard:nth-child(24) { left: 8%; top: -20px; animation-delay: 4s; height: 18px; }
    .hero-shard:nth-child(25) { right: 12%; top: -25px; animation-delay: 1.2s; height: 22px; width: 3px; background: linear-gradient(180deg, rgba(100,180,255,0.5), transparent); }
    .hero-shard:nth-child(26) { right: 12%; top: -25px; animation-delay: 5.2s; height: 22px; width: 3px; background: linear-gradient(180deg, rgba(100,180,255,0.5), transparent); }
    .hero-shard:nth-child(27) { left: 25%; top: -15px; animation-delay: 2.4s; height: 15px; width: 2px; background: linear-gradient(180deg, rgba(255,180,80,0.4), transparent); }
    .hero-shard:nth-child(28) { left: 25%; top: -15px; animation-delay: 6.4s; height: 15px; width: 2px; background: linear-gradient(180deg, rgba(255,180,80,0.4), transparent); }
    .hero-shard:nth-child(29) { right: 30%; top: -22px; animation-delay: 3.6s; height: 20px; }
    .hero-shard:nth-child(30) { right: 30%; top: -22px; animation-delay: 7.6s; height: 20px; }
    @keyframes shardFall {
        0% { transform: translateY(0) rotate(0deg); opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { transform: translateY(100vh) rotate(180deg); opacity: 0; }
    }

    .hero-beam {
        position: absolute; pointer-events: none;
        width: 2px; height: 100%;
        top: 0;
        background: linear-gradient(180deg, transparent, rgba(140,82,255,0.15), transparent);
        animation: beamSway 8s ease-in-out infinite;
    }
    .hero-beam:nth-child(31) { left: 15%; animation-delay: 0s; }
    .hero-beam:nth-child(32) { right: 15%; animation-delay: 0.8s; background: linear-gradient(180deg, transparent, rgba(100,180,255,0.15), transparent); }
    .hero-beam:nth-child(33) { left: 50%; animation-delay: 1.6s; }
    @keyframes beamSway {
        0%, 100% { transform: translateX(0); opacity: 0.3; }
        50% { transform: translateX(25px); opacity: 0.9; }
    }

    .hero-wave {
        position: absolute; bottom: 0; left: 0; right: 0;
        height: 120px; pointer-events: none; z-index: 2;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120'%3E%3Cpath d='M0,60 C200,120 400,0 600,60 C800,120 1000,0 1200,60 L1200,120 L0,120Z' fill='%23faf8ff' opacity='0.8'/%3E%3C/svg%3E") repeat-x bottom;
        background-size: 1200px 120px;
        animation: waveMove 10s linear infinite;
    }
    .hero-wave:nth-child(34) {
        opacity: 0.6;
        animation: waveMove 14s linear infinite reverse;
        background-position-x: 200px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120'%3E%3Cpath d='M0,60 C200,120 400,0 600,60 C800,120 1000,0 1200,60 L1200,120 L0,120Z' fill='%238c52ff' opacity='0.15'/%3E%3C/svg%3E");
    }
    @keyframes waveMove {
        0% { background-position-x: 0; }
        100% { background-position-x: 1200px; }
    }

    .hero-title {
        opacity: 0;
        animation: heroTitleIn 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        position: relative;
    }
    .hero-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, transparent, var(--purple), transparent);
        border-radius: 2px;
    }
    @keyframes heroTitleIn {
        0% { opacity: 0; transform: translateY(30px) scale(0.96); letter-spacing: -4px; }
        100% { opacity: 1; transform: translateY(0) scale(1); letter-spacing: normal; }
    }

    .hero-sub {
        opacity: 0;
        animation: heroFadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.5s forwards;
    }
    .hero-cta {
        opacity: 0;
        animation: heroFadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.7s forwards;
    }
    @keyframes heroFadeIn {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .hero-scroll {
        margin-top: 2.5rem;
        opacity: 0;
        animation: heroFadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) 1s forwards, scrollBounce 2s ease-in-out 1.8s infinite;
        cursor: pointer;
    }
    .hero-scroll i {
        display: block;
        margin: 0 auto;
    }
    @keyframes scrollBounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(6px); }
    }

    .section-tag {
        animation: tagReveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    .section-title {
        opacity: 0;
        animation: titleReveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes titleReveal {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

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

    .step-content {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 20px;
        border: 1px solid rgba(140,82,255,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .step-content:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(140,82,255,0.1);
    }

    .timeline-line {
        animation: timelineGlow 4s ease-in-out infinite;
    }
    @keyframes timelineGlow {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 1; }
    }

    .ministry-card {
        border-radius: 18px !important;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s !important;
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(140,82,255,0.06) !important;
    }
    .ministry-card:hover {
        transform: translateY(-8px) !important;
        box-shadow: 0 20px 50px rgba(140, 82, 255, 0.15) !important;
        background: #fff;
    }

    .carousel-btn {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        border: 1px solid rgba(140,82,255,0.15) !important;
    }
    .carousel-btn:hover {
        background: var(--purple) !important;
        box-shadow: 0 8px 25px var(--purple-glow) !important;
        transform: translateY(-50%) scale(1.1) !important;
    }
    .carousel-btn:hover i { color: #fff !important; }

    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 100;
        justify-content: center; align-items: center;
        animation: overlayIn 0.3s ease;
    }
    .modal-overlay.show { display: flex; }
    @keyframes overlayIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }
    .modal-box {
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 2.5rem; border-radius: 24px; text-align: center;
        width: 90%; max-width: 400px;
        box-shadow: 0 30px 80px rgba(0,0,0,0.2);
        position: relative;
        animation: modalPop 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes modalPop {
        0% { opacity: 0; transform: scale(0.9) translateY(20px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-box h3 { color: #8c52ff; font-weight: 700; margin-bottom: 0.75rem; }
    .modal-box p { color: #666; font-size: 0.95rem; margin-bottom: 1.5rem; }
    .modal-btn { display: block; width: 80%; margin: 0.75rem auto; padding: 12px; }
    .modal-close {
        position: absolute; top: 12px; right: 16px; border: none; background: none;
        font-size: 1.5rem; color: #999; cursor: pointer; line-height: 1; transition: color 0.2s, transform 0.2s;
    }
    .modal-close:hover { color: #333; transform: rotate(90deg); }
    .modal-input {
        width: 90%; padding: 12px; border: 2px solid #8c52ff; border-radius: 10px;
        font-size: 1.3rem; text-align: center; letter-spacing: 4px; font-weight: bold;
        color: #8c52ff; margin: 0.5rem auto 1rem; display: block; outline: none;
        background: rgba(255,255,255,0.5);
        transition: box-shadow 0.3s;
    }
    .modal-input:focus { box-shadow: 0 0 0 4px rgba(140,82,255,0.2); }
    .privacy-row { font-size: 0.8rem; color: #999; margin-top: 1rem; display: flex; align-items: center; justify-content: center; gap: 6px; }
    .privacy-row a { color: #8c52ff; cursor: pointer; text-decoration: underline; }

    .dove-trigger {
        position: fixed; bottom: 24px; left: 24px; z-index: 50;
        background: none; border: none; cursor: pointer; text-align: center; width: 100px;
        transition: transform 0.3s;
    }
    .dove-trigger:hover { transform: scale(1.08); }
    .dove-trigger img { width: 60px; transition: transform 0.3s, filter 0.3s; filter: drop-shadow(0 4px 12px rgba(140,82,255,0.15)); }
    .dove-trigger:hover img { transform: scale(1.12); filter: drop-shadow(0 8px 24px rgba(140,82,255,0.3)); }
    .dove-trigger p { font-size: 0.7rem; color: #aaa; margin: 4px 0 0; transition: color 0.3s; }
    .dove-trigger:hover p { color: var(--purple); }

    .verse-overlay.show { display: flex; }
    .verse-dove { width: 80px; margin: 0 auto; display: none; }
    .verse-box { opacity: 0; transition: opacity 1s; margin-top: 1rem; }
    .verse-box.show { opacity: 1; }
    .verse-box h4 { color: #8c52ff; margin-bottom: 0.5rem; }
    .verse-box p { font-style: italic; line-height: 1.6; }

    @media (max-width: 1023px) { .hero-content { margin-left: auto; margin-right: auto; text-align: center; align-items: center; } }
</style>
@endpush

@section('content')
    <section id="home" class="min-vh-100 bg-cover d-flex align-items-center justify-content-center bg-hero" style="background-image: url('{{ asset('images/banner.png') }}'); position: relative; overflow: hidden;">
        <div class="hero-glow"></div>
        <div class="hero-glow"></div>
        <div class="hero-glow"></div>
        <div class="hero-glow"></div>

        <div class="hero-ring"></div>
        <div class="hero-ring"></div>
        <div class="hero-ring"></div>
        <div class="hero-ring"></div>

        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>

        <div class="hero-shard"></div>
        <div class="hero-shard"></div>
        <div class="hero-shard"></div>
        <div class="hero-shard"></div>
        <div class="hero-shard"></div>
        <div class="hero-shard"></div>
        <div class="hero-shard"></div>
        <div class="hero-shard"></div>

        <div class="hero-beam"></div>
        <div class="hero-beam"></div>
        <div class="hero-beam"></div>

        <div class="hero-wave"></div>
        <div class="hero-wave"></div>

        <div class="hero-content text-center" style="position: relative; z-index: 10; padding: 0 20px;">
            <h1 class="display-1 fw-bold mb-5 hero-title" style="color: #8c52ff;">
                PERFIT
            </h1>
            <p class="text-muted mb-4 hero-sub" style="font-size: 1.125rem; max-width: 500px; margin-left: auto; margin-right: auto; line-height: 1.8;">
                Helping churches discover the right volunteers for every ministry through AI-powered matching.
            </p>
            <div class="hero-cta">
                <button class="btn btn-lg primary-btn-perfit" onclick="openModal('overlayUser')" style="padding-left: 3.5rem; padding-right: 3.5rem;">
                    Take Assessment
                </button>
            </div>
            <div class="hero-scroll">
                <i class="ti ti-chevron-down" style="font-size: 1.8rem; color: #8c52ff; display: block;"></i>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="py-5 position-relative" style="background: linear-gradient(135deg, #faf8ff 0%, #f0e6ff 50%, #faf8ff 100%);">
        <div class="container position-relative">
            <div class="text-center mb-5 section-tag">
                <span class="d-inline-block px-3 py-1 rounded-pill fw-semibold text-uppercase mb-3" style="font-size: .75rem; letter-spacing: .1em; background: #ebdefb; color: #8c52ff;">Simple Process</span>
                <h2 class="display-5 fw-bold mb-3">How It <span style="color: #8c52ff;">Works</span></h2>
                <p class="text-muted mx-auto" style="max-width: 540px;">Follow these simple steps to complete your assessment and discover your fit.</p>
            </div>

            @php $steps = [
                ['num' => '01', 'title' => 'Choose User Type', 'desc' => 'Select Leader to set restrictions or Volunteer to take the assessment.', 'icon' => 'ti ti-users'],
                ['num' => '02', 'title' => 'Enter Church Code', 'desc' => 'Use your church\'s code to apply your pastor\'s settings.', 'icon' => 'ti ti-key'],
                ['num' => '03', 'title' => 'Choose Language', 'desc' => 'Choose your preferred language.', 'icon' => 'ti ti-language'],
                ['num' => '04', 'title' => 'Answer the Assessment', 'desc' => 'Carefully go through each question and select your answer.', 'icon' => 'ti ti-clipboard-text'],
                ['num' => '05', 'title' => 'Submit the Assessment', 'desc' => 'Once done, Submit your response for evaluation.', 'icon' => 'ti ti-send'],
                ['num' => '06', 'title' => 'See the Result', 'desc' => 'After submission, check your personalized result.', 'icon' => 'ti ti-chart-bar'],
            ]; @endphp

            <div class="position-relative">
                <div class="position-absolute start-50 top-0 bottom-0 d-none d-md-block timeline-line" style="width: 3px; background: linear-gradient(180deg, #8c52ff00, #8c52ff, #8c52ff, #8c52ff00); translate: -50%;"></div>

                @foreach($steps as $i => $step)
                @php $side = $i % 2 === 0 ? 'left' : 'right'; @endphp

                @if($side === 'left')
                <div class="row g-0 mb-5 step-card align-items-center">
                    <div class="col-md-5 text-md-end">
                        <div class="step-content p-4" style="margin-right: -10px;">
                            <span class="badge fw-bold border-0 rounded-pill px-3 py-1.5 mb-2 p-3" style="background: linear-gradient(135deg, #8c52ff, #a978ff); color: #fff; font-size: .65rem; letter-spacing: .08em;">STEP {{ $step['num'] }}</span>
                            <h4 class="fw-bold mb-2" style="color: #1a1a2e;">{{ $step['title'] }}</h4>
                            <p class="text-muted mb-0" style="max-width: 380px; margin-left: auto; line-height: 1.7;">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-2 d-none d-md-flex justify-content-center position-relative z-1">
                        <div class="step-dot d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background: #fff; border: 3px solid #8c52ff; box-shadow: 0 0 0 8px rgba(140, 82, 255, 0.08);">
                            <i class="{{ $step['icon'] }}" style="font-size: 1.4rem; color: #8c52ff;"></i>
                        </div>
                    </div>
                    <div class="col-md-5 d-none d-md-block"></div>
                </div>
                @else
                <div class="row g-0 mb-5 step-card align-items-center">
                    <div class="col-md-5 d-none d-md-block"></div>
                    <div class="col-md-2 d-none d-md-flex justify-content-center position-relative z-1">
                        <div class="step-dot d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background: #fff; border: 3px solid #8c52ff; box-shadow: 0 0 0 8px rgba(140, 82, 255, 0.08);">
                            <i class="{{ $step['icon'] }}" style="font-size: 1.4rem; color: #8c52ff;"></i>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="step-content p-4" style="margin-left: -10px;">
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

    <section id="ministries" class="py-5 position-relative" style="background: linear-gradient(180deg, #fff 0%, #f8f4ff 100%); overflow: hidden;">
        <div class="position-absolute top-0 end-0 w-50 h-50 opacity-25" style="background: radial-gradient(circle, #8c52ff20 0%, transparent 70%); pointer-events: none;"></div>
        <div class="position-absolute bottom-0 start-0 w-50 h-50 opacity-40" style="background: radial-gradient(circle, #e2cffa 0%, transparent 70%); pointer-events: none;"></div>
        <div class="container position-relative">
            <div class="text-center mb-5 section-tag">
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
                                <div class="card h-100 border-0 shadow-sm ministry-card">
                                    <div style="height: 4px; background: {{ $m['color'] }}; border-radius: 18px 18px 0 0;"></div>
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

                <button class="carousel-control-prev carousel-btn" type="button" data-bs-target="#ministryCarousel" data-bs-slide="prev" style="width: 44px; height: 44px; top: 50%; transform: translateY(-50%); background: #fff; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,.12); left: 0; opacity: 1;">
                    <i class="ti ti-chevron-left" style="font-size: 1.4rem; color: #8c52ff;"></i>
                </button>
                <button class="carousel-control-next carousel-btn" type="button" data-bs-target="#ministryCarousel" data-bs-slide="next" style="width: 44px; height: 44px; top: 50%; transform: translateY(-50%); background: #fff; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,.12); right: 0; opacity: 1;">
                    <i class="ti ti-chevron-right" style="font-size: 1.4rem; color: #8c52ff;"></i>
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
