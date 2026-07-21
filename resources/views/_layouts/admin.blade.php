<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PERFIT Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>
<body>
    <livewire:toast-message />

    <!-- Sidebar overlay for mobile -->
    <div class="sidebarOverlay" id="sidebarOverlay"></div>

    <div class="adminPage">
        @include('_partials.adminSide.sideNav')

        <div class="adminRight">
            @include('_partials.adminSide.topNav')

            <main class="adminContent" id="adminContent">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        // Desktop sidebar toggle
        const sidebar = document.getElementById('adminSidebar');
        const toggleBtn = document.getElementById('sidebarToggle');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
            });
        }

        // Mobile hamburger toggle
        const hamburger = document.getElementById('mobileHamburger');
        const overlay = document.getElementById('sidebarOverlay');

        if (hamburger && sidebar && overlay) {
            hamburger.addEventListener('click', function() {
                sidebar.classList.toggle('mobileOpen');
                overlay.classList.toggle('show');
            });

            overlay.addEventListener('click', function() {
                sidebar.classList.remove('mobileOpen');
                overlay.classList.remove('show');
            });
        }

        // Logout form
        document.getElementById('sidebarLogoutForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch(this.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(res => res.json()).then(data => {
                if (data.success) window.location.href = '/admin/login';
            });
        });
    </script>

    @livewireScripts
</body>
</html>
