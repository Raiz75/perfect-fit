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

    @php $cp = $currentPhase ?? 1; @endphp
    <div id="phase1" @if($cp != 1) style="display:none;" @endif>
        @include('_partials.assessmentSide.wizard-demographics')
    </div>

    <div id="phase2" @if($cp != 2) style="display:none;" @endif>
        @if($skillQuestions->isNotEmpty())
            @include('_partials.assessmentSide.wizard-skills')
        @elseif($cp == 2)
            <p>No skill questions available for your church.</p>
        @endif
    </div>

    <div id="phase3" @if($cp != 3) style="display:none;" @endif>
        @if($interestQuestions->isNotEmpty())
            @include('_partials.assessmentSide.wizard-interest-and-passion')
        @elseif($cp == 3)
            <p>No interest & passion questions available for your church.</p>
        @endif
    </div>

    <div id="phase4" @if($cp != 4) style="display:none;" @endif>
        @include('_partials.assessmentSide.wizard-behavioral')
    </div>

    @include('_partials.assessmentSide.footer')

    @vite(['resources/js/assessment.js'])
</body>
</html>
