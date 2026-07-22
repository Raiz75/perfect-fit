<header class="banner">
    <img src="{{ asset('images/banner-cut.png') }}" alt="Banner">
    <p class="ttl">ASSESSMENT</p>
    <a href="{{ route('assessment.reset') }}" class="resetBtn" style="position:absolute; top:10px; right:10px; padding:8px 16px; background:rgb(128,65,128); color:white; text-decoration:none; border-radius:8px; font-size:0.9rem; z-index:10;">&#x21BA; Reset</a>
</header>

<div class="stepCounter">
    @php $cp = $currentPhase ?? 1; @endphp
    <div class="stepContainer">
        <p class="step {{ $cp > 1 ? 'completedStep' : ($cp == 1 ? 'currentStep' : '') }}" id="s1">1</p>
        <p>Personal Details</p>
    </div>
    <p class="line" id="l1"></p>
    <div class="stepContainer">
        <p class="step {{ $cp > 2 ? 'completedStep' : ($cp == 2 ? 'currentStep' : '') }}" id="s2">2</p>
        <p>Skill Profiling</p>
    </div>
    <p class="line" id="l2"></p>
    <div class="stepContainer">
        <p class="step {{ $cp > 3 ? 'completedStep' : ($cp == 3 ? 'currentStep' : '') }}" id="s3">3</p>
        <p>Interest & Passion Profiling</p>
    </div>
    <p class="line" id="l3"></p>
    <div class="stepContainer">
        <p class="step {{ $cp > 4 ? 'completedStep' : ($cp == 4 ? 'currentStep' : '') }}" id="s4">4</p>
        <p>Behavioral Profiling</p>
    </div>
    <p class="line" id="l4"></p>
    <div class="stepContainer">
        <p class="step {{ $cp > 5 ? 'completedStep' : ($cp == 5 ? 'currentStep' : '') }}" id="s5">5</p>
        <p>Result</p>
    </div>
</div>
