<nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 lg:h-24">
            <div class="flex-shrink-0">
                <a href="/" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Perfit Logo" class="h-14 w-auto max-w-full lg:h-20">
                </a>
            </div>

            <div class="hidden md:flex items-center gap-8 lg:gap-10">
                <a href="#" class="text-gray-700 hover:text-violet-600 font-medium transition-colors duration-200 text-sm lg:text-base">Home</a>
                <a href="#" class="text-gray-700 hover:text-violet-600 font-medium transition-colors duration-200 text-sm lg:text-base">How it Work</a>
                <a href="#" class="text-gray-700 hover:text-violet-600 font-medium transition-colors duration-200 text-sm lg:text-base">Ministry</a>
            </div>

            <div class="hidden md:block">
                <a href="#" class="inline-flex items-center px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-full text-sm lg:text-base transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                    Take Assessment
                </a>
            </div>

            <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors duration-200" aria-label="Toggle menu">
                <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="close-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white">
        <div class="px-4 py-4 space-y-3">
            <a href="#" class="block px-4 py-2.5 text-gray-700 hover:text-violet-600 hover:bg-violet-50 font-medium rounded-lg transition-colors duration-200">Home</a>
            <a href="#" class="block px-4 py-2.5 text-gray-700 hover:text-violet-600 hover:bg-violet-50 font-medium rounded-lg transition-colors duration-200">How it Work</a>
            <a href="#" class="block px-4 py-2.5 text-gray-700 hover:text-violet-600 hover:bg-violet-50 font-medium rounded-lg transition-colors duration-200">Ministry</a>
            <div class="pt-2">
                <a href="#" class="block text-center px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-full text-sm transition-all duration-200 shadow-sm">
                    Take Assessment
                </a>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');

        menuBtn.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        });
    });
</script>
@endpush
