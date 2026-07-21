@extends('_layouts.admin')

@section('title', 'Demographic Restrictions - PERFIT')
@section('pageTitle', 'Restriction Editor')

@section('content')
    @include('_partials.adminSide.restriction-topNav')

    <div class="card border-0 shadow-sm mt-3" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3 gap-2">
                <div>
                    <h5 class="fw-semibold mb-1">Demographic Restrictions</h5>
                    <p class="text-muted small mb-0">
                        Set restrictions for each ministry based on your <strong>church's requirements</strong>.
                        Select gender, age range, marital status, baptized status, and time in faith.
                        Select '<strong>No Restriction</strong>' if the rule does not apply.
                    </p>
                </div>
                <div class="d-flex gap-2 flex-shrink-0">
                    <button class="noToAdmin btn btn-outline-secondary btn-sm" id="resetDemographicsBtn">Reset all to default</button>
                    <button class="btn btn-primary btn-sm" id="saveDemographicsBtn">Save all changes</button>
                </div>
            </div>
            <div style="overflow-x: auto; max-width: 100%; -webkit-overflow-scrolling: touch;">
                <table class="table table-bordered table-hover align-middle mb-0" id="demographicsRestrictions" style="width: 100%; min-width: 800px;">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 140px;">Ministry Name</th>
                            <th style="min-width: 160px;">Gender Restriction</th>
                            <th style="min-width: 140px;">Age Restriction</th>
                            <th style="min-width: 160px;">Marital Status</th>
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
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;">
                                            <input type="radio" name="gender{{ $r->ministry_id }}" value="1" {{ $r->gender == 1 ? 'checked' : '' }}>
                                            Male Only
                                        </label>
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;">
                                            <input type="radio" name="gender{{ $r->ministry_id }}" value="2" {{ $r->gender == 2 ? 'checked' : '' }}>
                                            Female Only
                                        </label>
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;">
                                            <input type="radio" name="gender{{ $r->ministry_id }}" value="0" {{ $r->gender == 0 ? 'checked' : '' }}>
                                            No Restriction
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <input type="number" class="form-control form-control-sm age-min" style="width:65px;" min="1" max="99" value="{{ $r->age_min ?? 1 }}" placeholder="Min">
                                        <span class="text-muted">–</span>
                                        <input type="number" class="form-control form-control-sm age-max" style="width:65px;" min="1" max="99" value="{{ $r->age_max ?? 99 }}" placeholder="Max">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;">
                                            <input type="radio" name="marital{{ $r->ministry_id }}" value="1" {{ $r->marital_status == 1 ? 'checked' : '' }}>
                                            Single Only
                                        </label>
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;">
                                            <input type="radio" name="marital{{ $r->ministry_id }}" value="2" {{ $r->marital_status == 2 ? 'checked' : '' }}>
                                            Married Only
                                        </label>
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;">
                                            <input type="radio" name="marital{{ $r->ministry_id }}" value="0" {{ $r->marital_status == 0 ? 'checked' : '' }}>
                                            No Restriction
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-center gap-1" style="cursor:pointer;">
                                        <label class="switch mb-0">
                                            <input type="checkbox" class="baptized-check" data-ministry-id="{{ $r->ministry_id }}" {{ $r->baptized == 1 ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                        <span class="small baptized-label" data-ministry-id="{{ $r->ministry_id }}">{{ $r->baptized == 1 ? 'Baptized Only' : 'No Restriction' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;">
                                            <input type="radio" name="faith{{ $r->ministry_id }}" value="1" {{ $r->time_in_faith == 1 ? 'checked' : '' }}>
                                            1+ Week
                                        </label>
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;">
                                            <input type="radio" name="faith{{ $r->ministry_id }}" value="2" {{ $r->time_in_faith == 2 ? 'checked' : '' }}>
                                            6+ Months
                                        </label>
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;">
                                            <input type="radio" name="faith{{ $r->ministry_id }}" value="3" {{ $r->time_in_faith == 3 ? 'checked' : '' }}>
                                            1+ Year
                                        </label>
                                        <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;font-weight:400;">
                                            <input type="radio" name="faith{{ $r->ministry_id }}" value="4" {{ $r->time_in_faith == 4 ? 'checked' : '' }}>
                                            2+ Years
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No demographic restrictions found.</td>
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
                <h5 style="margin:0;font-weight:600;">Reset Demographic Restrictions</h5>
                <button class="modalCloseBtn" id="resetModalCloseBtn">&times;</button>
            </div>
            <div class="modalBoxBody">
                <p>This will replace all demographic restrictions with the default template. Any changes you made will be permanently lost.</p>
                <ul>
                    <li>Gender, age, marital, baptized, and time in faith rules across all ministries</li>
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

    // Baptized toggle label update
    document.querySelectorAll('.baptized-check').forEach(cb => {
        cb.addEventListener('change', function () {
            const id = this.dataset.ministryId;
            document.querySelector(`.baptized-label[data-ministry-id="${id}"]`).textContent =
                this.checked ? 'Baptized Only' : 'No Restriction';
        });
    });

    // Save
    document.getElementById('saveDemographicsBtn')?.addEventListener('click', function () {
        const rows = document.querySelectorAll('#demographicsRestrictions tbody tr[data-ministry-id]');
        const restrictions = [];

        rows.forEach(row => {
            const mid = row.dataset.ministryId;
            restrictions.push({
                id: row.dataset.id,
                ministry_id: mid,
                gender: document.querySelector(`input[name="gender${mid}"]:checked`)?.value || '0',
                age_min: row.querySelector('.age-min')?.value || 1,
                age_max: row.querySelector('.age-max')?.value || 99,
                marital_status: document.querySelector(`input[name="marital${mid}"]:checked`)?.value || '0',
                baptized: document.querySelector(`.baptized-check[data-ministry-id="${mid}"]`)?.checked ? '1' : '2',
                time_in_faith: document.querySelector(`input[name="faith${mid}"]:checked`)?.value || '1',
            });
        });

        if (!restrictions.length) {
            notify('No restrictions to save.', 'warning');
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.textContent = 'Saving...';

        fetch('{{ route("admin.restrictions.demographics.update") }}', {
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

    document.getElementById('resetDemographicsBtn')?.addEventListener('click', openResetModal);
    resetModalCancel?.addEventListener('click', closeResetModal);
    resetModalClose?.addEventListener('click', closeResetModal);
    resetModalOverlay?.addEventListener('click', function (e) { if (e.target === this) closeResetModal(); });

    resetModalConfirm?.addEventListener('click', function () {
        closeResetModal();
        const btn = document.getElementById('resetDemographicsBtn');
        btn.disabled = true;
        btn.textContent = 'Resetting...';

        fetch('{{ route("admin.restrictions.demographics.reset") }}', {
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
