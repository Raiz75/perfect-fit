@extends('_layouts.admin')

@section('title', 'Skill Restrictions - PERFIT')
@section('pageTitle', 'Restriction Editor')

@section('content')
    @include('_partials.adminSide.restriction-topNav')

    <div class="card border-0 shadow-sm mt-3" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3 gap-2">
                <div>
                    <h5 class="fw-semibold mb-1">Skill Restrictions</h5>
                    <p class="text-muted small mb-0">
                        Set the skill requirements for each ministry.
                        For each skill, toggle whether it is <strong>Required</strong> or <strong>Not Required</strong> for the ministry role.
                    </p>
                </div>
                <div class="d-flex gap-2 flex-shrink-0">
                    <button class="noToAdmin btn btn-outline-secondary btn-sm" id="resetSkillsBtn">Reset all to default</button>
                    <button class="btn btn-primary btn-sm" id="saveSkillsBtn">Save all changes</button>
                </div>
            </div>
            <div style="overflow-x: auto; max-width: 100%; -webkit-overflow-scrolling: touch;">
                <table class="table table-bordered table-hover align-middle mb-0" id="skillsRestrictions" style="width: 100%; min-width: 900px;">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 140px;">Ministry Name</th>
                            <th>Music</th>
                            <th>Technology</th>
                            <th>Writing</th>
                            <th>Technical</th>
                            <th>Speaking</th>
                            <th>Accounting</th>
                            <th>Mentoring</th>
                            <th>Bible Knowledge</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($restrictions as $r)
                            <tr data-id="{{ $r->id }}" data-ministry-id="{{ $r->ministry_id }}">
                                <td class="fw-medium">{{ $r->ministry->name }}</td>
                                @php
                                    $skillFields = ['music', 'technology', 'writing', 'technical', 'speaking', 'accounting', 'mentoring', 'bible_knowledge'];
                                    $skillLabels = ['Music', 'Technology', 'Writing', 'Technical', 'Speaking', 'Accounting', 'Mentoring', 'Bible Knowledge'];
                                @endphp
                                @foreach($skillFields as $idx => $field)
                                    <td>
                                        <div class="d-flex flex-column align-items-center gap-1" style="cursor:pointer;">
                                            <label class="switch mb-0">
                                                <input type="checkbox" class="skill-check" data-field="{{ $field }}" data-ministry-id="{{ $r->ministry_id }}" {{ $r->$field == 1 ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                            <span class="small skill-label" data-field="{{ $field }}" data-ministry-id="{{ $r->ministry_id }}">{{ $r->$field == 1 ? 'Required' : 'Not Required' }}</span>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No skill restrictions found.</td>
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
                <h5 style="margin:0;font-weight:600;">Reset Skill Restrictions</h5>
                <button class="modalCloseBtn" id="resetModalCloseBtn">&times;</button>
            </div>
            <div class="modalBoxBody">
                <p>This will replace all skill restrictions with the default template. Any changes you made will be permanently lost.</p>
                <ul>
                    <li>Required/Not Required toggles for 8 skills across all ministries</li>
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

    // Skill toggle label update
    document.querySelectorAll('.skill-check').forEach(cb => {
        cb.addEventListener('change', function () {
            const mid = this.dataset.ministryId;
            const field = this.dataset.field;
            document.querySelector(`.skill-label[data-field="${field}"][data-ministry-id="${mid}"]`).textContent =
                this.checked ? 'Required' : 'Not Required';
        });
    });

    // Save
    document.getElementById('saveSkillsBtn')?.addEventListener('click', function () {
        const rows = document.querySelectorAll('#skillsRestrictions tbody tr[data-ministry-id]');
        const restrictions = [];
        const fields = ['music', 'technology', 'writing', 'technical', 'speaking', 'accounting', 'mentoring', 'bible_knowledge'];

        rows.forEach(row => {
            const mid = row.dataset.ministryId;
            const data = { id: row.dataset.id, ministry_id: mid };
            fields.forEach(f => {
                const cb = document.querySelector(`.skill-check[data-field="${f}"][data-ministry-id="${mid}"]`);
                data[f] = cb?.checked ? 1 : 0;
            });
            restrictions.push(data);
        });

        if (!restrictions.length) {
            notify('No restrictions to save.', 'warning');
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.textContent = 'Saving...';

        fetch('{{ route("admin.restrictions.skills.update") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ restrictions }),
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

    // Reset modal
    const resetModalOverlay = document.getElementById('resetModalOverlay');
    const resetModalConfirm = document.getElementById('resetModalConfirm');
    const resetModalCancel = document.getElementById('resetModalCancel');
    const resetModalClose = document.getElementById('resetModalCloseBtn');

    function openResetModal() { resetModalOverlay.style.display = 'flex'; }
    function closeResetModal() { resetModalOverlay.style.display = 'none'; }

    document.getElementById('resetSkillsBtn')?.addEventListener('click', openResetModal);
    resetModalCancel?.addEventListener('click', closeResetModal);
    resetModalClose?.addEventListener('click', closeResetModal);
    resetModalOverlay?.addEventListener('click', function (e) { if (e.target === this) closeResetModal(); });

    resetModalConfirm?.addEventListener('click', function () {
        closeResetModal();
        const btn = document.getElementById('resetSkillsBtn');
        btn.disabled = true;
        btn.textContent = 'Resetting...';

        fetch('{{ route("admin.restrictions.skills.reset") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                notify(data.message);
                if (data.restored) location.reload();
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
