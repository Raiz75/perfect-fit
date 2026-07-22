<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment — PERFIT</title>
    @vite(['resources/css/app.css'])
</head>
<body data-current-phase="{{ $currentPhase ?? 1 }}">
    <div class="assessment-bg">
        <div class="assessment-float" style="width:450px;height:450px;background:radial-gradient(circle,#8c52ff,transparent);top:-12%;left:-10%;animation:assessFloatA 16s ease-in-out infinite;"></div>
        <div class="assessment-float" style="width:350px;height:350px;background:radial-gradient(circle,#b388ff,transparent);bottom:-10%;right:-8%;animation:assessFloatB 20s ease-in-out infinite;"></div>
        <div class="assessment-float" style="width:220px;height:220px;background:radial-gradient(circle,#d4b3ff,transparent);top:38%;left:-5%;animation:assessFloatA 14s ease-in-out infinite reverse;"></div>

        <div class="assessment-container">
            <div class="assessment-title">
                <h1>PER<span>FIT</span></h1>
            </div>

            @include('_partials.assessmentSide.header')

            @php $cp = $currentPhase ?? 1; @endphp

            <div id="phase1" class="phase-section" @if($cp != 1) style="display:none;" @endif>
                @include('_partials.assessmentSide.wizard-demographics')
            </div>

            <div id="phase2" class="phase-section" @if($cp != 2) style="display:none;" @endif>
                @if($skillQuestions->isNotEmpty())
                    @include('_partials.assessmentSide.wizard-skills')
                @elseif($cp == 2)
                    <p style="text-align:center;color:#5a35b0;">No skill questions available for your church.</p>
                @endif
            </div>

            <div id="phase3" class="phase-section" @if($cp != 3) style="display:none;" @endif>
                @if($interestQuestions->isNotEmpty())
                    @include('_partials.assessmentSide.wizard-interest-and-passion')
                @elseif($cp == 3)
                    <p style="text-align:center;color:#5a35b0;">No interest &amp; passion questions available for your church.</p>
                @endif
            </div>

            <div id="phase4" class="phase-section" @if($cp != 4) style="display:none;" @endif>
                @if($behavioralQuestions->isNotEmpty())
                    @include('_partials.assessmentSide.wizard-behavioral')
                @elseif($cp == 4)
                    <p style="text-align:center;color:#5a35b0;">No behavioral questions available for your eligible ministries.</p>
                @endif
            </div>

            <div id="phase5" class="phase-section" @if($cp != 5) style="display:none;" @endif>
                @include('_partials.assessmentSide.wizard-results')
            </div>

            @include('_partials.assessmentSide.footer')
        </div>
    </div>

    @vite(['resources/js/assessment.js'])
</body>
</html>
