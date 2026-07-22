<div class="assessmentFooter">
    <div class="footerContent">
        <div class="footerLeft">
            <div class="puzzle">
                <img src="{{ asset('images/pzl-top.png') }}" class="top" id="top" alt="">
                <img src="{{ asset('images/pzl-right.png') }}" class="right" id="right" alt="">
                <img src="{{ asset('images/pzl-left.png') }}" class="left" id="left" alt="">
                <img src="{{ asset('images/pzl-bottom.png') }}" class="bottom" id="bottom" alt="">
            </div>
        </div>
        <div class="footerRight">
            @if(($currentPhase ?? 1) == 5)
                <form method="POST" action="{{ route('assessment.done') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn primary-btn-perfit" id="doneBtn">
                        <i class="ti ti-home" style="margin-right:6px;"></i> Done
                    </button>
                </form>
            @else
                <button type="submit" class="btn primary-btn-perfit" id="nextPhaseBtn"
                    form="{{ ($currentPhase ?? 1) == 1 ? 'demographicForm' : (($currentPhase ?? 1) == 2 ? 'skillsForm' : (($currentPhase ?? 1) == 3 ? 'interestForm' : (($currentPhase ?? 1) == 4 ? 'behavioralForm' : 'demographicForm'))) }}">
                    <i class="ti ti-arrow-right" style="margin-right:6px;"></i>Next Phase
                </button>
                <button class="btn primary-btn-perfit" id="submitBtn" style="display:none;">
                    <i class="ti ti-send" style="margin-right:6px;"></i>Submit
                </button>
            @endif
        </div>
    </div>
    <div class="footerBg">
        <img src="{{ asset('images/footer.png') }}" alt="">
    </div>
</div>
