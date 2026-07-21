@extends('_layouts.admin')

@section('title', 'Skill Questions - PERFIT')
@section('pageTitle', 'Question Editor')

@section('content')
    @include('_partials.adminSide.question-topNav')

    <div class="card border-0 shadow-sm mt-3" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3 gap-2">
                <div>
                    <h5 class="fw-semibold mb-1">Skill Questions</h5>
                    <p class="text-muted small mb-0">
                        Edit the skill profiling questions to assess volunteer <strong>abilities</strong> in specific areas.
                        Click on a cell to edit the text directly.
                    </p>
                </div>
                <div class="d-flex gap-2 flex-shrink-0">
                    <button class="noToAdmin btn btn-outline-secondary btn-sm" id="resetSkillBtn">Reset all to default</button>
                    <button class="btn btn-primary btn-sm" id="saveSkillBtn">Save all changes</button>
                </div>
            </div>
            <div style="overflow-x: auto; max-width: 100%; -webkit-overflow-scrolling: touch;">
                <table class="table table-bordered table-hover align-middle mb-0" id="skillsQuestions" style="width: 100%; min-width: 650px;">
                    <thead class="table-light">
                        <tr>
                            <th>Skill</th>
                            <th style="width: 140px;">Question Number</th>
                            <th>English Question</th>
                            <th>Tagalog Question</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $skillName => $group)
                            @foreach($group as $question)
                                <tr data-id="{{ $question->id }}">
                                    <td>{{ $skillName }}</td>
                                    <td>{{ $question->question_number }}</td>
                                    <td class="editable" contenteditable="true">{{ $question->question_en }}</td>
                                    <td class="editable" contenteditable="true">{{ $question->question_tl }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No questions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modalOverlay" id="resetModalOverlay">
        <div class="modalBox">
            <div class="modalBoxHeader">
                <h5 style="margin:0;font-weight:600;">Reset Skill Questions</h5>
                <button class="modalCloseBtn" id="resetModalCloseBtn">&times;</button>
            </div>
            <div class="modalBoxBody">
                <p>This will replace all skill questions with the default template. Any changes you made will be permanently lost.</p>
                <ul>
                    <li>40 skill questions across 8 skill areas</li>
                    <li>English and Tagalog translations will be reset</li>
                    <li>This action <strong>cannot be undone</strong></li>
                </ul>
            </div>
            <div class="modalBoxFooter">
                <button class="btn btn-secondary" id="resetModalCancel">Cancel</button>
                <button class="btn btn-danger" id="resetModalConfirm">Confirm Reset</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function notify(text, type = 'success') {
        window.dispatchEvent(new CustomEvent('notify', { detail: { text, type } }));
    }

    document.getElementById('saveSkillBtn')?.addEventListener('click', function () {
        const rows = document.querySelectorAll('#skillsQuestions tbody tr[data-id]');
        const questions = [];

        rows.forEach(row => {
            const cells = row.querySelectorAll('td.editable');
            if (cells.length < 2) return;
            questions.push({
                id: row.dataset.id,
                question_en: cells[0].textContent.trim(),
                question_tl: cells[1].textContent.trim(),
            });
        });

        if (!questions.length) {
            notify('No questions to save.', 'warning');
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.textContent = 'Saving...';

        fetch('{{ route("admin.questions.skill.update") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ questions }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) notify(data.message);
            else notify(data.message || 'Failed to save.', 'danger');
        })
        .catch(() => notify('Network error. Please try again.', 'danger'))
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Save all changes';
        });
    });

    const resetModalOverlay = document.getElementById('resetModalOverlay');
    const resetModalConfirm = document.getElementById('resetModalConfirm');
    const resetModalCancel = document.getElementById('resetModalCancel');
    const resetModalClose = document.getElementById('resetModalCloseBtn');

    function openResetModal() {
        resetModalOverlay.style.display = 'flex';
    }

    function closeResetModal() {
        resetModalOverlay.style.display = 'none';
    }

    document.getElementById('resetSkillBtn')?.addEventListener('click', openResetModal);

    resetModalCancel?.addEventListener('click', closeResetModal);
    resetModalClose?.addEventListener('click', closeResetModal);
    resetModalOverlay?.addEventListener('click', function (e) {
        if (e.target === this) closeResetModal();
    });

    resetModalConfirm?.addEventListener('click', function () {
        closeResetModal();

        const btn = document.getElementById('resetSkillBtn');
        btn.disabled = true;
        btn.textContent = 'Resetting...';

        fetch('{{ route("admin.questions.skill.reset") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                notify(data.message);
                if (data.questions) location.reload();
            } else {
                notify(data.message || 'Failed to reset.', 'danger');
            }
        })
        .catch(() => notify('Network error. Please try again.', 'danger'))
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Reset all to default';
        });
    });
});
</script>
@endpush

