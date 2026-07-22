<form method="POST" action="{{ route('assessment.phase4.store') }}" id="behavioralForm">
    @csrf

    @if($eligibleMinistries->isEmpty())
        <div style="text-align:center; padding:3rem;">
            <p style="font-size:1.3rem; color:#6b3fa0;">
                It appears that your interest or personal profile doesn't match with the ministries' requirements yet.
                We encourage you to continue growing in your faith and spiritual journey.
                When the time is right, we would love to see you involved.
                God bless you on your path of growth and readiness.
            </p>
        </div>
        <input type="hidden" name="no_match" value="1">
    @else
        <p style="font-weight:bold;">
            INSTRUCTION: Please read each statement carefully and answer honestly.
            Use the scale 1-6 — Strongly Agree(6) to Strongly Disagree(1).
        </p>

        @php $prevMinistryId = null; @endphp

        @foreach($behavioralQuestions as $question)
            @php $ministryName = $question->ministry->name ?? 'Unknown'; @endphp

            @if($question->ministry_id !== $prevMinistryId)
                @if($prevMinistryId !== null)
                    </fieldset>
                @endif
                <fieldset>
                    <legend>{{ $ministryName }}</legend>
                @php $prevMinistryId = $question->ministry_id; @endphp
            @endif

            <div style="margin-bottom:1.5rem; padding:0.5rem; border:1px solid #ddd;">
                <p><strong>Q{{ $question->question_number }}:</strong> {{ $question->question_en }}</p>
                <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                    @foreach([6,5,4,3,2,1] as $val)
                        <label style="border:1px solid #ccc; padding:0.3rem 0.6rem; cursor:pointer;">
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $val }}" required>
                            {{ $val }}
                        </label>
                    @endforeach
                </div>
                @error("answers.{{ $question->id }}") <span style="color:red;">{{ $message }}</span> @enderror
            </div>
        @endforeach

        @if($prevMinistryId !== null)
            </fieldset>
        @endif
    @endif
</form>
