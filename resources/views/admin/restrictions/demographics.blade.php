@extends('_layouts.admin')

@section('title', 'Demographic Restrictions - PERFIT')
@section('pageTitle', 'Restriction Editor')

@section('content')
    @include('_partials.adminSide.restriction-topNav')

    <div class="admin-glass-card p-4 mt-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3 gap-2">
            <div>
                <h5 class="admin-section-title">Demographic Restrictions</h5>
                <p class="admin-section-desc mb-0">
                    Set restrictions for each ministry based on your <strong>church's requirements</strong>.
                    Select gender, age range, marital status, baptized status, and time in faith.
                </p>
            </div>
            <div class="admin-action-bar mb-0">
                <button class="btn btn-outline-perfit noToAdmin" id="resetDemographicsBtn">Reset to default</button>
                <button class="btn primary-btn-perfit btn-sm" id="saveDemographicsBtn">Save changes</button>
            </div>
        </div>
        <div class="admin-glass-table">
            <div style="overflow-x: auto; max-width: 100%;">
                <table class="table align-middle mb-0" id="demographicsRestrictions" style="min-width: 800px;">
                    <thead>
                        <tr>
                            <th style="min-width: 140px;">Ministry</th>
                            <th style="min-width: 160px;">Gender</th>
                            <th style="min-width: 140px;">Age</th>
                            <th style="min-width: 160px;">Marital</th>
                            <th style="min-width: 140px;">Baptized</th>
                            <th style="min-width: 180px;">Time in Faith</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($restrictions as $r)
                            <tr data-id="{{ $r->id }}" data-ministry-id="{{ $r->ministry_id }}">
                                <td class="fw-medium">{{ $r->ministry->name }}</td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;font-size:13px;">
                                            <input type="radio" name="gender{{ $r->ministry_id }}" value="1" {{ $r->gender == 1 ? 'checked' : '' }}> Male Only
                                        </label>
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;font-size:13px;">
                                            <input type="radio" name="gender{{ $r->ministry_id }}" value="2" {{ $r->gender == 2 ? 'checked' : '' }}> Female Only
                                        </label>
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;font-size:13px;">
                                            <input type="radio" name="gender{{ $r->ministry_id }}" value="0" {{ $r->gender == 0 ? 'checked' : '' }}> No Restriction
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <input type="number" class="form-control form-control-sm age-min" style="width:60px;" min="1" max="99" value="{{ $r->age_min ?? 1 }}" placeholder="Min">
                                        <span class="text-muted">–</span>
                                        <input type="number" class="form-control form-control-sm age-max" style="width:60px;" min="1" max="99" value="{{ $r->age_max ?? 99 }}" placeholder="Max">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;font-size:13px;">
                                            <input type="radio" name="marital{{ $r->ministry_id }}" value="1" {{ $r->marital_status == 1 ? 'checked' : '' }}> Single
                                        </label>
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;font-size:13px;">
                                            <input type="radio" name="marital{{ $r->ministry_id }}" value="2" {{ $r->marital_status == 2 ? 'checked' : '' }}> Married
                                        </label>
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;font-size:13px;">
                                            <input type="radio" name="marital{{ $r->ministry_id }}" value="0" {{ $r->marital_status == 0 ? 'checked' : '' }}> No Restriction
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-center gap-1" style="cursor:pointer;">
                                        <label class="switch mb-0">
                                            <input type="checkbox" class="baptized-check" data-ministry-id="{{ $r->ministry_id }}" {{ $r->baptized == 1 ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                        <span class="small baptized-label">{{ $r->baptized == 1 ? 'Baptized Only' : 'No Restriction' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @foreach(['1'=>'1+ Week','2'=>'6+ Months','3'=>'1+ Year','4'=>'2+ Years'] as $val => $label)
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;font-size:13px;">
                                            <input type="radio" name="faith{{ $r->ministry_id }}" value="{{ $val }}" {{ $r->time_in_faith == $val ? 'checked' : '' }}> {{ $label }}
                                        </label>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No demographic restrictions found.</td></tr>
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
                <p>This will replace all demographic restrictions with the default template. This cannot be undone.</p>
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

    document.querySelectorAll('.baptized-check').forEach(cb => {
        cb.addEventListener('change', function () {
            this.closest('td').querySelector('.baptized-label').textContent = this.checked ? 'Baptized Only' : 'No Restriction';
        });
    });

    document.getElementById('saveDemographicsBtn')?.addEventListener('click', function () {
        const rows = document.querySelectorAll('#demographicsRestrictions tbody tr[data-ministry-id]');
        const restrictions = [];
        rows.forEach(row => {
            const mid = row.dataset.ministryId;
            restrictions.push({
                id: row.dataset.id, ministry_id: mid,
                gender: document.querySelector(`input[name="gender${mid}"]:checked`)?.value || '0',
                age_min: row.querySelector('.age-min')?.value || 1,
                age_max: row.querySelector('.age-max')?.value || 99,
                marital_status: document.querySelector(`input[name="marital${mid}"]:checked`)?.value || '0',
                baptized: document.querySelector(`.baptized-check[data-ministry-id="${mid}"]`)?.checked ? '1' : '2',
                time_in_faith: document.querySelector(`input[name="faith${mid}"]:checked`)?.value || '1',
            });
        });
        if (!restrictions.length) return notify('No restrictions to save.', 'warning');
        const btn = this; btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';
        fetch('{{ route("admin.restrictions.demographics.update") }}', {
            method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ restrictions }),
        }).then(r => r.json()).then(d => { notify(d.message, d.success ? 'success' : 'danger'); }).catch(() => notify('Network error.', 'danger'))
        .finally(() => { btn.disabled = false; btn.textContent = 'Save changes'; });
    });

    const modal = document.getElementById('resetModalOverlay');
    document.getElementById('resetDemographicsBtn')?.addEventListener('click', () => modal.style.display = 'flex');
    document.getElementById('resetModalCancel')?.addEventListener('click', () => modal.style.display = 'none');
    document.getElementById('resetModalCloseBtn')?.addEventListener('click', () => modal.style.display = 'none');
    modal?.addEventListener('click', function (e) { if (e.target === this) this.style.display = 'none'; });
    document.getElementById('resetModalConfirm')?.addEventListener('click', function () {
        modal.style.display = 'none';
        const btn = document.getElementById('resetDemographicsBtn'); btn.disabled = true; btn.textContent = 'Resetting...';
        fetch('{{ route("admin.restrictions.demographics.reset") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } })
        .then(r => r.json()).then(d => { if (d.success) { notify(d.message); if (d.restored) location.reload(); } else notify(d.message||'Failed.', 'danger'); })
        .catch(() => notify('Network error.', 'danger')).finally(() => { btn.disabled = false; btn.textContent = 'Reset to default'; });
    });
});
</script>
@endpush