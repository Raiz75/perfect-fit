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



    @foreach($interestQuestions as $question)
        <div class="question-card {{ $loop->first ? '' : 'blurred' }}">
            <p class="question-text" style="text-align:center;">
                {{ $question->question_en }}
            </p>
            <div class="likert-row">
                @foreach([6,5,4,3,2,1] as $val)
                    <label class="likert-btn">
                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $val }}" required
                            onchange="var r=this.closest('.likert-row');r.querySelectorAll('.likert-btn').forEach(function(b){b.classList.remove('selected')});this.closest('.likert-btn').classList.add('selected');var c=this.closest('.question-card');c.classList.remove('blurred');c.classList.add('answered');var n=c.nextElementSibling;while(n&&!n.matches('.question-card'))n=n.nextElementSibling;if(n){n.classList.remove('blurred');n.scrollIntoView({behavior:'smooth',block:'center'});}">
                        {{ $val }}
                    </label>
                @endforeach
            </div>
            @error("answers.{{ $question->id }}") <span class="assessment-error">{{ $message }}</span> @enderror
        </div>
    @endforeach
</form>
