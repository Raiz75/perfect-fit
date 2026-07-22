<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-email" content="{{ Auth::user()->email ?? '' }}">
    <title>@yield('title', 'PERFIT Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>
<body>
    <livewire:toast-message />

    <!-- Floating background shapes for admin -->
    <div class="adminFloatShape"></div>
    <div class="adminFloatShape"></div>
    <div class="adminFloatShape"></div>

    <!-- Sidebar overlay for mobile -->
    <div class="sidebarOverlay" id="sidebarOverlay"></div>

    <!-- Logout Confirmation Modal -->
    <div class="logout-modal-overlay" id="logoutModal">
        <div class="logout-modal-box">
            <div class="logout-modal-icon">
                <i class="ti ti-logout"></i>
            </div>
            <h3 class="logout-modal-title">Confirm Logout</h3>
            <p class="logout-modal-text">Are you sure you want to log out?</p>
            <div class="logout-modal-actions">
                <button class="btn btn-secondary logout-btn-cancel" onclick="document.getElementById('logoutModal').classList.remove('show')">Cancel</button>
                <form method="POST" action="{{ route('admin.logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn primary-btn-perfit">Yes, Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="adminPage">
        @include('_partials.adminSide.sideNav')

        <div class="adminRight">
            @include('_partials.adminSide.topNav')

            <main class="adminContent adminGlassBg" id="adminContent">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    @vite(['resources/js/admin.js'])
    @livewireScripts
</body>
</html>
