<form method="POST" action="{{ route('assessment.phase3.store') }}" id="interestForm">
    @csrf

    @if($errors->any())
        <div class="alert-box">
            @foreach($errors->all() as $err)
                <p>{{ $err }}</p>
            @endforeach
        </div>
    @endif

    <div class="assessment-instruction">
        <i class="ti ti-info-circle" style="margin-right:6px;"></i>
        Please read each statement carefully and answer honestly.
        Use the scale <strong>1–6</strong> — Strongly Agree (6) to Strongly Disagree (1).
    </div>

    @php
        $prevCatId = null;
        $catIcons = [
            1 => 'ti ti-star',
            2 => 'ti ti-heart-handshake',
            3 => 'ti ti-world',
            4 => 'ti ti-palette',
            5 => 'ti ti-heart',
            6 => 'ti ti-users',
        ];
    @endphp

    @foreach($interestQuestions as $question)
        @php $catName = $ministryCategories[$question->ministry_category_id]->name ?? 'Unknown'; @endphp

        @if($question->ministry_category_id !== $prevCatId)
            <div class="category-header">
                <div class="cat-icon">
                    <i class="{{ $catIcons[$question->ministry_category_id] ?? 'ti ti-star' }}"></i>
                </div>
                <h3>{{ $catName }}</h3>
            </div>
            @php $prevCatId = $question->ministry_category_id; @endphp
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
</form>
