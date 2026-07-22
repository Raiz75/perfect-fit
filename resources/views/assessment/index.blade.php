<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment</title>
    @vite(['resources/css/app.css'])
    @livewireStyles
</head>
<body>
    @include('_partials.assessmentSide.header')

    <livewire:demographic-wizard />

    @include('_partials.assessmentSide.footer')

    @livewireScripts
</body>
</html>
