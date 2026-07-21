@extends('_layouts.admin')

@section('title', 'Behavioral Questions - PERFIT')
@section('pageTitle', 'Question Editor')

@section('content')
    @include('_partials.adminSide.question-topNav')

    <div class="admin-glass-card p-4 mt-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3 gap-2">
            <div>
                <h5 class="admin-section-title">Behavioral Questions</h5>
                <p class="admin-section-desc mb-0">Edit behavioral questions to assess volunteer <strong>traits</strong> and <strong>ministry fit</strong>.</p>
            </div>
            <div class="admin-action-bar mb-0">
                <button class="btn btn-outline-perfit noToAdmin" id="resetBehavioralBtn">Reset</button>
                <button class="btn primary-btn-perfit btn-sm" id="saveBehavioralBtn">Save changes</button>
            </div>
        </div>
        <div class="admin-glass-table">
            <div style="overflow-x: auto; max-width: 100%;">
                <table class="table align-middle mb-0" id="behavioralQuestions" style="min-width: 650px;">
                    <thead><tr><th>Ministry</th><th style="width:140px;">#</th><th>English</th><th>Tagalog</th></tr></thead>
                    <tbody>
                        @forelse($questions as $ministryName => $group)
                            @foreach($group as $question)
                                <tr data-id="{{ $question->id }}">
                                    <td class="fw-medium">{{ $ministryName }}</td>
                                    <td>{{ $question->question_number }}</td>
                                    <td class="editable" contenteditable="true">{{ $question->question_en }}</td>
                                    <td class="editable" contenteditable="true">{{ $question->question_tl }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No questions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modalOverlay" id="resetModalOverlay">
        <div class="modalBox">
            <div class="modalBoxHeader"><h5>Reset Questions</h5><button class="modalCloseBtn">&times;</button></div>
            <div class="modalBoxBody"><p>This will replace all questions with defaults. Cannot be undone.</p></div>
            <div class="modalBoxFooter">
                <button class="btn btn-secondary">Cancel</button>
                <button class="btn btn-danger">Confirm Reset</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const notify = (t, type = 'success') => window.dispatchEvent(new CustomEvent('notify', { detail: { text: t, type } }));
    const modal = document.getElementById('resetModalOverlay');
    document.getElementById('saveBehavioralBtn')?.addEventListener('click', function () {
        const rows = document.querySelectorAll('#behavioralQuestions tbody tr[data-id]'); const qs = [];
        rows.forEach(r => { const c = r.querySelectorAll('td.editable'); if(c.length>=2) qs.push({id:r.dataset.id,question_en:c[0].textContent.trim(),question_tl:c[1].textContent.trim()}); });
        if(!qs.length) return notify('No questions.','warning');
        const btn=this; btn.disabled=true; btn.innerHTML='<span class="spinner-border spinner-border-sm me-1"></span>Saving...';
        fetch('{{ route("admin.questions.behavioral.update") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken},body:JSON.stringify({questions:qs})})
        .then(r=>r.json()).then(d=>{notify(d.message,d.success?'success':'danger')}).catch(()=>notify('Error.','danger'))
        .finally(()=>{btn.disabled=false;btn.textContent='Save changes';});
    });
    document.getElementById('resetBehavioralBtn')?.addEventListener('click',()=>modal.style.display='flex');
    modal.querySelectorAll('.modalCloseBtn,.btn-secondary')?.forEach(b=>b?.addEventListener('click',()=>modal.style.display='none'));
    modal?.addEventListener('click',function(e){if(e.target===this)this.style.display='none';});
    modal.querySelector('.btn-danger')?.addEventListener('click',function(){
        modal.style.display='none';
        fetch('{{ route("admin.questions.behavioral.reset") }}',{method:'POST',headers:{'X-CSRF-TOKEN':csrfToken}})
        .then(r=>r.json()).then(d=>{if(d.success){notify(d.message);if(d.questions)location.reload();}else notify(d.message||'Failed.','danger');})
        .catch(()=>notify('Error.','danger'));
    });
});
</script>
@endpush