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

    <!-- Floating background shapes for admin -->
    <div class="adminFloatShape"></div>
    <div class="adminFloatShape"></div>
    <div class="adminFloatShape"></div>

    <!-- Sidebar overlay for mobile -->
    <div class="sidebarOverlay" id="sidebarOverlay"></div>

    <div class="adminPage">
        <x-admin-sidebar />

        <div class="adminRight">
            <x-admin-top-nav :title="$title ?? 'PERFIT'" />

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
