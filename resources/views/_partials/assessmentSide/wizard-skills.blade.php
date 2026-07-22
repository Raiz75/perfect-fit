<form method="POST" action="{{ route('assessment.phase2.store') }}" id="skillsForm">
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
        $prevSkillId = null;
        $skillIcons = [
            1 => 'ti ti-music',
            2 => 'ti ti-device-laptop',
            3 => 'ti ti-pencil',
            4 => 'ti ti-tools',
            5 => 'ti ti-microphone',
            6 => 'ti ti-calculator',
            7 => 'ti ti-users',
            8 => 'ti ti-book',
        ];
    @endphp

    @foreach($skillQuestions as $question)
        @php $skillName = $skills[$question->skill_id]->name ?? 'Unknown'; @endphp

        @if($question->skill_id !== $prevSkillId)
            <div class="category-header">
                <div class="cat-icon">
                    <i class="{{ $skillIcons[$question->skill_id] ?? 'ti ti-star' }}"></i>
                </div>
                <h3>{{ $skillName }}</h3>
            </div>
            @php $prevSkillId = $question->skill_id; @endphp
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
