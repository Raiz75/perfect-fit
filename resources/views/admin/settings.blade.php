@extends('_layouts.admin')

@section('title', 'Settings - PERFIT')
@section('pageTitle', 'Settings')

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="admin-glass-card p-4 h-100">
            <h5 class="fw-semibold mb-1">Church Information</h5>
            <p class="text-muted small mb-3">Update your church details.</p>
            <hr>

            <div class="mb-3">
                <label class="form-label fw-medium">Church Name</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="churchNameInput"
                           value="{{ $user->church_name }}" placeholder="Enter church name">
                    <button class="btn primary-btn-perfit btn-sm" id="saveChurchNameBtn">Save</button>
                </div>
                <div class="invalid-feedback" id="churchNameError"></div>
            </div>

            <div class="mb-0">
                <label class="form-label fw-medium">Church Code</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="churchCodeInput"
                           value="{{ $user->church_code }}" readonly style="background:rgba(140,82,255,0.05);">
                    <button class="btn btn-outline-secondary" id="copyCodeBtn">Copy</button>
                </div>
                <div class="form-text">Share this code with volunteers so they can register.</div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="admin-glass-card p-4 h-100">
            <h5 class="fw-semibold mb-1">Personal Information</h5>
            <p class="text-muted small mb-3">Your admin account details.</p>
            <hr>

            <div class="mb-3">
                <label class="form-label fw-medium">Email</label>
                <input type="text" class="form-control" value="{{ $user->email }}" readonly style="background:rgba(140,82,255,0.05);">
            </div>

            <div class="mb-0">
                <label class="form-label fw-medium">Password</label>
                <div>
                    <button class="btn primary-btn-perfit btn-sm" id="changePassBtn">
                        Change password
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modalOverlay" id="passModalOverlay">
    <div class="modalBox">
        <div class="modalBoxHeader">
            <h5 style="margin:0;font-weight:600;">Change Password</h5>
            <button class="modalCloseBtn" id="passModalCloseBtn">&times;</button>
        </div>
        <div class="modalBoxBody">
            <div class="mb-3">
                <label class="form-label fw-medium">Current Password</label>
                <input type="password" class="form-control" id="currentPassword" required>
                <div class="invalid-feedback" id="currentPasswordError"></div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">New Password</label>
                <input type="password" class="form-control" id="newPassword" required>
                <div class="form-text">At least 8 characters, 1 capital letter, 1 number, and 1 special character.</div>
                <div class="invalid-feedback" id="newPasswordError"></div>
            </div>
            <div class="mb-0">
                <label class="form-label fw-medium">Confirm New Password</label>
                <input type="password" class="form-control" id="newPasswordConfirmation" required>
                <div class="invalid-feedback" id="newPasswordConfirmationError"></div>
            </div>
        </div>
        <div class="modalBoxFooter">
            <button class="btn btn-secondary" id="passModalCancelBtn">Cancel</button>
            <button class="btn primary-btn-perfit" id="savePasswordBtn">Update Password</button>
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

    document.getElementById('saveChurchNameBtn')?.addEventListener('click', function () {
        const input = document.getElementById('churchNameInput');
        const val = input.value.trim();

        if (!val) {
            input.classList.add('is-invalid');
            document.getElementById('churchNameError').textContent = 'Please enter a church name.';
            return;
        }
        input.classList.remove('is-invalid');

        fetch('{{ route("admin.settings.church-name") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ church_name: val }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                notify('Church name updated successfully.');
            } else {
                if (data.errors?.church_name) {
                    input.classList.add('is-invalid');
                    document.getElementById('churchNameError').textContent = data.errors.church_name[0];
                } else {
                    notify(data.message || 'Failed to update church name.', 'danger');
                }
            }
        })
        .catch(() => notify('Network error. Please try again.', 'danger'));
    });

    document.getElementById('copyCodeBtn')?.addEventListener('click', function () {
        const input = document.getElementById('churchCodeInput');
        navigator.clipboard.writeText(input.value).then(() => {
            const orig = this.textContent;
            this.textContent = 'Copied!';
            setTimeout(() => { this.textContent = orig; }, 2000);
        });
    });

    const overlay = document.getElementById('passModalOverlay');

    function openPassModal() {
        document.getElementById('currentPassword').value = '';
        document.getElementById('newPassword').value = '';
        document.getElementById('newPasswordConfirmation').value = '';
        document.querySelectorAll('#passModalOverlay .is-invalid').forEach(el => el.classList.remove('is-invalid'));
        overlay.style.display = 'flex';
    }

    function closePassModal() {
        overlay.style.display = 'none';
    }

    function validatePassword(pw) {
        return pw.length >= 8 && /[A-Z]/.test(pw) && /\d/.test(pw) && /[^A-Za-z0-9]/.test(pw);
    }

    document.getElementById('changePassBtn')?.addEventListener('click', openPassModal);
    document.getElementById('passModalCloseBtn')?.addEventListener('click', closePassModal);
    document.getElementById('passModalCancelBtn')?.addEventListener('click', closePassModal);
    overlay?.addEventListener('click', function (e) {
        if (e.target === this) closePassModal();
    });

    document.getElementById('savePasswordBtn')?.addEventListener('click', function () {
        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const newPasswordConfirmation = document.getElementById('newPasswordConfirmation').value;

        document.querySelectorAll('#passModalOverlay .is-invalid').forEach(el => el.classList.remove('is-invalid'));

        let hasError = false;
        if (!currentPassword) {
            document.getElementById('currentPassword').classList.add('is-invalid');
            document.getElementById('currentPasswordError').textContent = 'Current password is required.';
            hasError = true;
        }
        if (!newPassword) {
            document.getElementById('newPassword').classList.add('is-invalid');
            document.getElementById('newPasswordError').textContent = 'New password is required.';
            hasError = true;
        } else if (!validatePassword(newPassword)) {
            document.getElementById('newPassword').classList.add('is-invalid');
            document.getElementById('newPasswordError').textContent = 'Must be at least 8 characters with 1 capital letter, 1 number, and 1 special character.';
            hasError = true;
        }
        if (newPassword !== newPasswordConfirmation) {
            document.getElementById('newPasswordConfirmation').classList.add('is-invalid');
            document.getElementById('newPasswordConfirmationError').textContent = 'Passwords do not match.';
            hasError = true;
        }
        if (hasError) return;

        const btn = this;
        btn.disabled = true;
        btn.textContent = 'Updating...';

        fetch('{{ route("admin.settings.password") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword,
                new_password_confirmation: newPasswordConfirmation,
            }),
        })
        .then(r => r.json().then(data => ({ status: r.status, data })))
        .then(({ status, data }) => {
            if (data.success) {
                closePassModal();
                notify('Password updated successfully.');
            } else {
                if (data.errors?.current_password) {
                    document.getElementById('currentPassword').classList.add('is-invalid');
                    document.getElementById('currentPasswordError').textContent = data.errors.current_password[0];
                }
                if (data.errors?.new_password) {
                    document.getElementById('newPassword').classList.add('is-invalid');
                    document.getElementById('newPasswordError').textContent = data.errors.new_password[0];
                }
                if (!data.errors?.current_password) {
                    notify(data.message || 'Failed to update password.', 'danger');
                }
            }
        })
        .catch(() => notify('Network error. Please try again.', 'danger'))
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Update Password';
        });
    });
});
</script>
@endpush