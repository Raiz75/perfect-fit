<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfit</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .float-img { position: absolute; z-index: 1; animation: sway 6s ease-in-out infinite; object-fit: cover; border-radius: 1rem; box-shadow: 0 20px 60px rgba(0,0,0,0.4);}
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
        .step-dot:nth-child(1) { animation-delay: 0s; }
        .step-dot:nth-child(2) { animation-delay: 0.5s; }
        .step-dot:nth-child(3) { animation-delay: 1s; }
        .step-dot:nth-child(4) { animation-delay: 1.5s; }
        .step-dot:nth-child(5) { animation-delay: 2s; }
        .step-dot:nth-child(6) { animation-delay: 2.5s; }

        @keyframes breathe {
            0%, 100% { box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.3); transform: scale(1); }
            50% { box-shadow: 0 0 0 12px rgba(139, 92, 246, 0); transform: scale(1.08); }
        }

        .timeline-pulse { animation: pulseLine 3s ease-in-out infinite; }
        @keyframes pulseLine {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 1; }
        }

        .glow-float { animation: glowFloat 6s ease-in-out infinite; }
        @keyframes glowFloat {
            0%, 100% { transform: translateY(0) scale(1); opacity: 0.6; }
            50% { transform: translateY(-20px) scale(1.05); opacity: 1; }
        }

        .hover-glow:hover { filter: drop-shadow(0 0 20px rgba(139, 92, 246, 0.4)); }

        .step-line { transition: height 1.5s cubic-bezier(0.16, 1, 0.3, 1); }
        .step-line.grow { height: 100%; }

        .number-glide { transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
        .number-glide:hover { transform: translateY(-4px); }

        @media (max-width: 1023px) { .float-img { display: none !important; } .hero-content { margin-left: auto; margin-right: auto; text-align: center; align-items: center; } }
        @media (min-width: 1024px) { .hero-content { margin-left: 4.5rem; } }
        @media (min-width: 1280px) { .hero-content { margin-left: 8.5rem; } }
    </style>
</head>
<body>
    @include('_partials.topnav')
    <br><br><br>
    <main>
        <section class="relative min-h-screen flex flex-col lg:flex-row items-start px-6 lg:px-16 pt-24 pb-12 overflow-hidden bg-cover bg-center" style="background-image: url('{{ asset('images/banner.png') }}')">
            <img src="{{ asset('images/bg.png') }}" alt="" class="float-img">
            <img src="{{ asset('images/bg.png') }}" alt="" class="float-img">

            <div class="hero-content flex flex-col items-center text-center max-w-xl relative z-10 mt-16 lg:mt-32">
                <h1 class="text-6xl sm:text-7xl font-extrabold leading-[1.05] mb-4 text-white">
                    PERFIT
                </h1>
                <p class="text-base sm:text-lg leading-relaxed mb-10 max-w-md">
                    PERFIT uses AI to match church volunteers with the right ministry roles, making service more meaningful and coordination effortless for leaders.
                </p>
                <a href="#" class="inline-flex items-center px-10 py-3.5 bg-violet-500 hover:bg-violet-400 text-white font-semibold rounded-full text-base transition-all duration-300 hover:scale-105 hover:shadow-[0_0_40px_rgba(139,92,246,0.3)]">
                    Take Assessment
                </a>
            </div>
        </section>

        <section class="relative py-28 px-6 lg:px-16 overflow-hidden bg-gradient-to-b from-gray-50 to-white">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-20 left-10 w-72 h-72 bg-violet-200/20 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 right-10 w-96 h-96 bg-violet-300/20 rounded-full blur-3xl"></div>
            </div>
            <div class="relative max-w-6xl mx-auto">
                <div class="text-center mb-20">
                    <h2 class="text-5xl font-extrabold text-gray-900 mb-4 tracking-tight">How It <span class="text-violet-500 underline decoration-violet-300 underline-offset-8 decoration-4">Works</span></h2>
                    <p class="text-gray-400 text-lg max-w-lg mx-auto">Follow these simple steps to complete your assessment and discover your fit.</p>
                </div>

                <div class="relative">
                    <div class="absolute left-1/2 -translate-x-px top-0 bottom-0 w-0.5 bg-gradient-to-b from-violet-200 via-violet-400 to-violet-200 hidden lg:block"></div>

                    @php $steps = [
                        ['num' => '01', 'title' => 'Choose User Type', 'desc' => 'Select Leader to set restrictions or Volunteer to take the assessment.', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'side' => 'left'],
                        ['num' => '02', 'title' => 'Enter Church Code', 'desc' => 'Use your church\'s code to apply your pastor\'s settings.', 'icon' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M15 14a3.001 3.001 0 012.83 2', 'side' => 'right'],
                        ['num' => '03', 'title' => 'Choose Language', 'desc' => 'Choose your preferred language.', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'side' => 'left'],
                        ['num' => '04', 'title' => 'Answer the Assessment', 'desc' => 'Carefully go through each question and select your answer.', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'side' => 'right'],
                        ['num' => '05', 'title' => 'Submit the Assessment', 'desc' => 'Once done, Submit your response for evaluation.', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'side' => 'left'],
                        ['num' => '06', 'title' => 'See the Result', 'desc' => 'After submission, check your personalized result.', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', 'side' => 'right'],
                    ]; @endphp

                    @foreach($steps as $i => $step)
                    <div class="step-card relative flex flex-col lg:flex-row items-center mb-16 last:mb-0 group">
                        <div class="flex-1 {{ $step['side'] === 'left' ? 'lg:text-right lg:pr-12' : 'lg:text-left lg:pl-12 lg:ml-auto' }} {{ $step['side'] === 'left' ? 'lg:order-1' : 'lg:order-3' }} w-full lg:w-[calc(50%-2rem)]">
                            <div class="inline-block lg:{{ $step['side'] === 'left' ? 'text-right' : 'text-left' }}">
                                <span class="text-xs font-bold tracking-[0.2em] text-violet-500 uppercase">{{ $step['num'] }}</span>
                                <h3 class="text-2xl font-bold text-gray-900 mt-1 mb-3">{{ $step['title'] }}</h3>
                                <p class="text-gray-400 leading-relaxed max-w-sm {{ $step['side'] === 'left' ? 'lg:ml-auto' : '' }}">{{ $step['desc'] }}</p>
                            </div>
                        </div>

                        <div class="step-dot relative z-10 flex items-center justify-center w-14 h-14 lg:w-16 lg:h-16 rounded-full bg-white border-2 border-violet-200 shadow-lg shadow-violet-100/50 group-hover:border-violet-500 group-hover:shadow-violet-200/50 transition-all duration-500 lg:order-2 my-4 lg:my-0 shrink-0">
                            <svg class="w-6 h-6 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $step['icon'] }}" />
                            </svg>
                        </div>

                        <div class="flex-1 {{ $step['side'] === 'left' ? 'lg:order-3' : 'lg:order-1' }} hidden lg:block"></div>
                    </div>
                    @endforeach
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
        });
    </script>
</body>
</html>
