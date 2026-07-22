<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    @include('_partials.assessmentSide.header')

    <div id="phase1">
        @include('components.⚡demographic-wizard')
    </div>

    <div id="phase2" style="display: none;">
        <p>Phase 2: Skill Profiling (Coming soon)</p>
    </div>

    @include('_partials.assessmentSide.footer')

    @vite(['resources/js/assessment.js'])
</body>
</html>
