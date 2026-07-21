@extends('_layouts.admin')

@section('title', 'Skill Restrictions - PERFIT')
@section('pageTitle', 'Restriction Editor')

@section('content')
    @include('_partials.adminSide.restriction-topNav')

    <div class="admin-glass-card p-4 mt-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3 gap-2">
            <div>
                <h5 class="admin-section-title">Skill Restrictions</h5>
                <p class="admin-section-desc mb-0">
                    Set the skill requirements for each ministry. Toggle <strong>Required</strong> or <strong>Not Required</strong>.
                </p>
            </div>
            <div class="admin-action-bar mb-0">
                <button class="btn btn-outline-perfit noToAdmin" id="resetSkillsBtn">Reset to default</button>
                <button class="btn primary-btn-perfit btn-sm" id="saveSkillsBtn">Save changes</button>
            </div>
        </div>
        <div class="admin-glass-table">
            <div style="overflow-x: auto; max-width: 100%;">
                <table class="table align-middle mb-0" id="skillsRestrictions" style="min-width: 900px;">
                    <thead>
                        <tr>
                            <th style="min-width: 140px;">Ministry</th>
                            @foreach(['Music','Technology','Writing','Technical','Speaking','Accounting','Mentoring','Bible Knowledge'] as $sk)
                                <th>{{ $sk }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($restrictions as $r)
                            <tr data-id="{{ $r->id }}" data-ministry-id="{{ $r->ministry_id }}">
                                <td class="fw-medium">{{ $r->ministry->name }}</td>
                                @php $skillFields = ['music','technology','writing','technical','speaking','accounting','mentoring','bible_knowledge']; @endphp
                                @foreach($skillFields as $field)
                                    <td>
                                        <div class="d-flex flex-column align-items-center gap-1" style="cursor:pointer;">
                                            <label class="switch mb-0">
                                                <input type="checkbox" class="skill-check" data-field="{{ $field }}" data-ministry-id="{{ $r->ministry_id }}" {{ $r->$field == 1 ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                            <span class="small skill-label">{{ $r->$field == 1 ? 'Required' : 'Not Required' }}</span>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted py-4">No skill restrictions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modalOverlay" id="resetModalOverlay">
        <div class="modalBox">
            <div class="modalBoxHeader">
                <h5 style="margin:0;font-weight:600;">Reset Restrictions</h5>
                <button class="modalCloseBtn" id="resetModalCloseBtn">&times;</button>
            </div>
            <div class="modalBoxBody">
                <p>This will replace all skill restrictions with defaults. Cannot be undone.</p>
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
    const notify = (t, type = 'success') => window.dispatchEvent(new CustomEvent('notify', { detail: { text: t, type } }));

    document.querySelectorAll('.skill-check').forEach(cb => {
        cb.addEventListener('change', function () {
            this.closest('td').querySelector('.skill-label').textContent = this.checked ? 'Required' : 'Not Required';
        });
    });

    document.getElementById('saveSkillsBtn')?.addEventListener('click', function () {
        const rows = document.querySelectorAll('#skillsRestrictions tbody tr[data-ministry-id]');
        const restrictions = [];
        const fields = ['music','technology','writing','technical','speaking','accounting','mentoring','bible_knowledge'];
        rows.forEach(row => {
            const mid = row.dataset.ministryId;
            const data = { id: row.dataset.id, ministry_id: mid };
            fields.forEach(f => { data[f] = document.querySelector(`.skill-check[data-field="${f}"][data-ministry-id="${mid}"]`)?.checked ? 1 : 0; });
            restrictions.push(data);
        });
        if (!restrictions.length) return notify('No restrictions to save.', 'warning');
        const btn = this; btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';
        fetch('{{ route("admin.restrictions.skills.update") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ restrictions }) })
        .then(r => r.json()).then(d => { notify(d.message, d.success ? 'success' : 'danger'); }).catch(() => notify('Network error.', 'danger'))
        .finally(() => { btn.disabled = false; btn.textContent = 'Save changes'; });
    });

    const modal = document.getElementById('resetModalOverlay');
    document.getElementById('resetSkillsBtn')?.addEventListener('click', () => modal.style.display = 'flex');
    document.getElementById('resetModalCancel')?.addEventListener('click', () => modal.style.display = 'none');
    document.getElementById('resetModalCloseBtn')?.addEventListener('click', () => modal.style.display = 'none');
    modal?.addEventListener('click', function(e) { if (e.target === this) this.style.display = 'none'; });
    document.getElementById('resetModalConfirm')?.addEventListener('click', function () {
        modal.style.display = 'none';
        const btn = document.getElementById('resetSkillsBtn'); btn.disabled = true; btn.textContent = 'Resetting...';
        fetch('{{ route("admin.restrictions.skills.reset") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } })
        .then(r => r.json()).then(d => { if (d.success) { notify(d.message); if (d.restored) location.reload(); } else notify(d.message||'Failed.', 'danger'); })
        .catch(() => notify('Network error.', 'danger')).finally(() => { btn.disabled = false; btn.textContent = 'Reset to default'; });
    });
});
</script>
@endpush