<form method="POST" action="{{ route('assessment.phase4.store') }}" id="behavioralForm">
    @csrf

    @if($errors->any())
        <div class="alert-box">
            @foreach($errors->all() as $err)
                <p>{{ $err }}</p>
            @endforeach
        </div>
    @endif

    @if($eligibleMinistries->isEmpty())
        <div class="no-match-box">
            <i class="ti ti-heart-broken no-match-icon"></i>
            <p>
                It appears that your interest or personal profile doesn't match with the ministries' requirements yet.
                We encourage you to continue growing in your faith and spiritual journey.
                When the time is right, we would love to see you involved.
                God bless you on your path of growth and readiness.
            </p>
        </div>
        <input type="hidden" name="no_match" value="1">
    @else
        <div class="assessment-instruction">
            <i class="ti ti-info-circle" style="margin-right:6px;"></i>
            Please read each statement carefully and answer honestly.
            Use the scale <strong>1–6</strong> — Strongly Agree (6) to Strongly Disagree (1).
        </div>

        @php $prevMinistryId = null; @endphp

        @foreach($behavioralQuestions as $question)
            @php $ministryName = $question->ministry->name ?? 'Unknown'; @endphp

            @if($question->ministry_id !== $prevMinistryId)
                <div class="category-header">
                    <div class="cat-icon">
                        <i class="ti ti-building-community"></i>
                    </div>
                    <h3>{{ $ministryName }}</h3>
                </div>
                @php $prevMinistryId = $question->ministry_id; @endphp
            @endif

            <div class="question-card">
                <p class="question-text">
                    <strong>Q{{ $question->question_number }}.</strong> {{ $question->question_en }}
                </p>
                <div class="likert-row">
                    @foreach([6,5,4,3,2,1] as $val)
                        <label class="likert-btn">
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $val }}" required
                                onchange="this.closest('.likert-row').querySelectorAll('.likert-btn').forEach(b=>b.classList.remove('selected')); this.closest('.likert-btn').classList.add('selected');">
                            {{ $val }}
                        </label>
                    @endforeach
                </div>
                @error("answers.{{ $question->id }}") <span class="assessment-error">{{ $message }}</span> @enderror
            </div>
        @endforeach
    @endif
</form>
