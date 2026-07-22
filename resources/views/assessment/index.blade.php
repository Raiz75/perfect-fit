<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment</title>
    @vite(['resources/css/app.css'])
</head>
<body data-current-phase="{{ $currentPhase ?? 1 }}">
    @include('_partials.assessmentSide.header')

    <div id="phase1" @if(($currentPhase ?? 1) > 1) style="display:none;" @endif>
        @include('_partials.assessmentSide.wizard-demographics')
    </div>

    <div id="phase2" @if(($currentPhase ?? 1) < 2) style="display:none;" @endif>
        @if($skillQuestions->isNotEmpty())
            @include('_partials.assessmentSide.wizard-skills')
        @elseif(($currentPhase ?? 1) >= 2)
            <p>No skill questions available for your church.</p>
        @endif
    </div>

    <div id="phase3" style="display:none;">
        <p>Phase 3: Interest & Passion Profiling (Coming soon)</p>
    </div>

    @include('_partials.assessmentSide.footer')

    @vite(['resources/js/assessment.js'])
</body>
</html>
