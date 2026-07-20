@extends('_layouts.admin')

@section('title', 'PERFIT - Admin Login')

@section('admin-nav') @endsection

@push('head')
    <link rel="icon" type="image/png" href="{{ asset('images/icn-logo.png') }}">
    <style>
        :root { --purple: #8c52ff; }
        .auth-card { border-radius: 25px; overflow: hidden; box-shadow: 0 20px 60px rgba(140, 82, 255, 0.15); border: 1px solid var(--purple); }
        .forms-wrapper { width: 200%; display: flex; transition: transform 0.6s cubic-bezier(0.645, 0.045, 0.355, 1); }
        #container.show-signup .forms-wrapper { transform: translateX(-50%); }
        .form-section { width: 50%; }
        .input-group-custom { border: 2px solid var(--purple); border-radius: 12px; display: flex; align-items: center; background: #fff; }
        .input-group-custom input { border: 2px solid transparent; background: #f8f9fa; border-radius: 12px; outline: none; width: 100%; padding: 14px 18px; font-size: 14px; }
        .input-group-custom input:focus { border-color: var(--purple) !important; }
        .input-group-custom img { height: 28px; width: 28px; margin: 10px; cursor: pointer; flex-shrink: 0; }
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(6px); z-index: 100; justify-content: center; align-items: center; }
        .modal-overlay.show { display: flex; }
        .auth-modal-box { background: #fff; padding: 2.5rem; border-radius: 20px; text-align: center; width: 90%; max-width: 360px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
    </style>
@endpush

@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #faf8ff 0%, #f0e6ff 100%); padding: 20px; position: relative;">
        <h1 class="position-absolute fw-bold" style="top: 6%; color: var(--purple); font-size: 2.5rem;">Admin Login</h1>

        <div class="auth-card bg-white" id="container">
            <div class="forms-wrapper">
                <div class="form-section p-4 p-md-5 d-flex flex-column align-items-center">
                    <h2 class="fw-bold mb-4" style="color: var(--purple);">Welcome Back</h2>
                    <form id="signinForm" class="w-100">
                        <div class="input-group-custom mb-3">
                            <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="input-group-custom mb-3">
                            <input type="password" name="password" id="signinPass" placeholder="Password" required>
                            <img src="{{ asset('images/icn-closedEyes.png') }}" alt="toggle" onclick="togglePassword('signinPass', this)">
                        </div>
                        <button type="submit" class="btn primary-btn-perfit d-block mx-auto mt-3">Sign In</button>
                    </form>
                    <div class="w-100 text-center mt-3">
                        <a id="forgotPasswordLink" class="text-decoration-none fw-semibold" style="color: var(--purple); cursor: pointer; font-size: 14px;">Forgot Password?</a>
                    </div>
                    <div class="mt-4 text-center" style="color: var(--purple); font-size: 14px;">
                        Don't have an account? <a id="showSignup" class="fw-semibold text-decoration-none" style="color: var(--purple); cursor: pointer;">Sign Up</a>
                    </div>
                </div>

                <div class="form-section p-4 p-md-5 d-flex flex-column align-items-center">
                    <h2 class="fw-bold mb-4" style="color: var(--purple);">Create Account</h2>
                    <form id="signupForm" class="w-100">
                        <div class="input-group-custom mb-3">
                            <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="input-group-custom mb-3">
                            <input type="password" name="password" id="signupPass" placeholder="Password" required>
                            <img src="{{ asset('images/icn-closedEyes.png') }}" alt="toggle" onclick="togglePassword('signupPass', this)">
                        </div>
                        <div class="input-group-custom mb-3">
                            <input type="password" id="confirmPass" placeholder="Confirm Password" required>
                            <img src="{{ asset('images/icn-closedEyes.png') }}" alt="toggle" onclick="togglePassword('confirmPass', this)">
                        </div>
                        <button type="submit" class="btn primary-btn-perfit d-block mx-auto mt-3">Sign Up</button>
                    </form>
                    <div class="mt-4 text-center" style="color: var(--purple); font-size: 14px;">
                        Already have an account? <a id="showSignin" class="fw-semibold text-decoration-none" style="color: var(--purple); cursor: pointer;">Sign In</a>
                    </div>
                </div>
            </div>
            <button class="w-100 border-0 py-3 fw-semibold" onclick="goHome()" style="background: #faf8ff; color: var(--purple); cursor: pointer; font-size: 14px; border-top: 1px solid #eee;">Go Home</button>
        </div>

        <div class="modal-overlay" id="verifyPopup">
            <div class="auth-modal-box">
                <h3 class="fw-bold mb-2" style="color: var(--purple);">Email Verification</h3>
                <p class="text-muted mb-3" style="font-size: 14px;">Enter the 6-digit code sent to your email.</p>
                <input type="text" id="verifyCode" maxlength="6" class="text-center form-control w-75 mx-auto mb-3" style="border: 2px solid var(--purple); border-radius: 8px; font-size: 18px; padding: 10px; letter-spacing: 4px; font-weight: bold; color: var(--purple);">
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn primary-btn-perfit" onclick="cancelVerification()" style="padding-left: 1.5rem; padding-right: 1.5rem;">Back</button>
                    <button class="btn primary-btn-perfit" onclick="checkVerificationCode()" style="padding-left: 1.5rem; padding-right: 1.5rem;">Verify</button>
                </div>
            </div>
        </div>

        <div class="modal-overlay" id="forgotPopup">
            <div class="auth-modal-box">
                <h3 class="fw-bold mb-2" style="color: var(--purple);">Reset Password</h3>
                <p class="text-muted mb-3" style="font-size: 14px;">Enter your registered email to receive a temporary password.</p>
                <input type="email" id="resetEmail" placeholder="Enter your email" class="form-control w-75 mx-auto mb-3" style="border: 2px solid var(--purple); border-radius: 8px; padding: 10px; text-align: center;">
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn primary-btn-perfit" onclick="closeForgotPopup()" style="padding-left: 1.5rem; padding-right: 1.5rem;">Back</button>
                    <button class="btn primary-btn-perfit" onclick="sendTemporaryPassword()" style="padding-left: 1.5rem; padding-right: 1.5rem;">Send</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const container = document.getElementById('container');
        const signinForm = document.getElementById('signinForm');
        const signupForm = document.getElementById('signupForm');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function showMessage(text, type) {
            Livewire.dispatch('notify', { text, type });
        }

        function togglePassword(id, img) {
            const input = document.getElementById(id);
            if (input.type === 'password') {
                input.type = 'text';
                img.src = '{{ asset("images/icn-openEyes.png") }}';
            } else {
                input.type = 'password';
                img.src = '{{ asset("images/icn-closedEyes.png") }}';
            }
        }

        function goHome() {
            window.location.href = '/';
        }

        document.getElementById('showSignup').addEventListener('click', () => container.classList.add('show-signup'));
        document.getElementById('showSignin').addEventListener('click', () => container.classList.remove('show-signup'));

        const jsonHeaders = { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken };

        signinForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(signinForm);
            fetch('/admin/login', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    setTimeout(() => { window.location.href = '/admin/dashboard'; }, 1000);
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(() => showMessage('Error connecting to server.', 'error'));
        });

        signupForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const email = signupForm.querySelector('input[name="email"]').value.trim();
            const pass = document.getElementById('signupPass').value.trim();
            const confirm = document.getElementById('confirmPass').value.trim();

            fetch('/admin/check-email', {
                method: 'POST',
                headers: jsonHeaders,
                body: JSON.stringify({ email })
            })
            .then(res => res.json())
            .then(data => {
                if (data.exists) {
                    showMessage('Email already exists. Please use another one.', 'error');
                    return;
                }
                if (pass !== confirm) {
                    showMessage('Passwords do not match!', 'error');
                    return;
                }
                if (pass.length < 6) {
                    showMessage('Password must be at least 6 characters.', 'error');
                    return;
                }
                showMessage('Processing verification code.', 'success');
                setTimeout(() => {
                    fetch('/admin/send-verification', {
                        method: 'POST',
                        headers: jsonHeaders,
                        body: JSON.stringify({ email })
                    })
                    .then(res => res.json())
                    .then(mailData => {
                        if (mailData.success) {
                            window.tempSignupData = { email, password: pass };
                            document.getElementById('verifyPopup').classList.add('show');
                            showMessage('Verification code sent to your email.', 'success');
                        } else {
                            showMessage(mailData.message, 'error');
                        }
                    })
                    .catch(() => showMessage('Failed to send verification email.', 'error'));
                }, 1500);
            })
            .catch(() => showMessage('Error checking email.', 'error'));
        });

        function checkVerificationCode() {
            const code = document.getElementById('verifyCode').value.trim();
            fetch('/admin/verify-code', {
                method: 'POST',
                headers: jsonHeaders,
                body: JSON.stringify({ code })
            })
            .then(res => res.json())
            .then(verifyData => {
                if (verifyData.success) {
                    const formData = new FormData();
                    formData.append('email', window.tempSignupData.email);
                    formData.append('password', window.tempSignupData.password);
                    fetch('/admin/register', {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showMessage('Email verified and account created! Please sign in.', 'success');
                            signupForm.reset();
                            document.getElementById('verifyPopup').classList.remove('show');
                            document.getElementById('showSignin').click();
                        } else {
                            showMessage(data.message, 'error');
                        }
                    })
                    .catch(() => showMessage('Error saving account.', 'error'));
                } else {
                    showMessage(verifyData.message, 'error');
                }
            })
            .catch(() => showMessage('Error verifying code.', 'error'));
        }

        function cancelVerification() {
            document.getElementById('verifyPopup').classList.remove('show');
            showMessage('Verification cancelled.', 'error');
        }

        document.getElementById('forgotPasswordLink').addEventListener('click', () => {
            document.getElementById('forgotPopup').classList.add('show');
        });

        function closeForgotPopup() {
            document.getElementById('forgotPopup').classList.remove('show');
            document.getElementById('resetEmail').value = '';
        }

        function sendTemporaryPassword() {
            const email = document.getElementById('resetEmail').value.trim();
            if (!email) {
                showMessage('Please enter your email.', 'error');
                return;
            }
            showMessage('Processing temporary password.', 'success');
            setTimeout(() => {
                fetch('/admin/forgot-password', {
                    method: 'POST',
                    headers: jsonHeaders,
                    body: JSON.stringify({ email })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showMessage('Temporary password sent to your email!', 'success');
                        closeForgotPopup();
                    } else {
                        showMessage(data.message, 'error');
                    }
                })
                .catch(() => showMessage('Error connecting to server.', 'error'));
            }, 1500);
        }
    </script>
@endpush
