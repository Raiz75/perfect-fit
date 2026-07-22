<form method="POST" action="{{ route('assessment.phase3.store') }}" id="interestForm">
    @csrf

    <p style="font-weight:bold;">INSTRUCTION: Please read each statement carefully and answer honestly. Use the scale 1-6 — Strongly Agree(6) to Strongly Disagree(1).</p>

    @php
        $prevCatId = null;
    @endphp

    @foreach($interestQuestions as $question)
        @php
            $catName = $ministryCategories[$question->ministry_category_id]->name ?? 'Unknown';
        @endphp

        @if($question->ministry_category_id !== $prevCatId)
            @if($prevCatId !== null)
                </fieldset>
            @endif
            <fieldset>
                <legend>{{ $catName }}</legend>
            @php $prevCatId = $question->ministry_category_id; @endphp
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

    @if($prevCatId !== null)
        </fieldset>
    @endif
</form>
