<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfit</title>
    @vite('resources/css/app.css')
    <style>
        .float-img { position: absolute; z-index: 1; animation: sway 6s ease-in-out infinite; object-fit: cover; border-radius: 1rem; box-shadow: 0 20px 60px rgba(0,0,0,0.4);}
        .float-img:nth-child(1) { width: 700px; height: 500px; top: 8%; right: 8%; animation-delay: 0s; }
        .float-img:nth-child(2) { width: 500px; height: 300px; top: 40%; right: 30%; animation-delay: -1.8s; }
        @keyframes sway { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-14px); } }
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
                <a href="#" class="inline-flex items-center px-10 py-3.5 bg-emerald-500 hover:bg-emerald-400 text-white font-semibold rounded-full text-base transition-all duration-300 hover:scale-105 hover:shadow-[0_0_40px_rgba(16,185,129,0.3)]">
                    Take Assessment
                </a>
            </div>
        </section>
    </main>

    @stack('scripts')
</body>
</html>
