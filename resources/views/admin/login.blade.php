<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PERFIT - Admin Login</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icn-logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        :root { --purple: #8c52ff; --purple-dark: #6a3dd9; --purple-glow: rgba(140, 82, 255, 0.35); }

        * { margin: 0; padding: 0; }
        body { font-family: system-ui, -apple-system, sans-serif; }

        .bg-animated {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #faf8ff 0%, #f0e6ff 50%, #e8d5ff 100%);
            background-size: 400% 400%;
            animation: gradientShift 12s ease infinite;
            padding: 20px; position: relative; overflow: hidden;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .float-shape {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            opacity: 0.15;
        }
        .float-shape:nth-child(1) {
            width: 400px; height: 400px;
            background: radial-gradient(circle, var(--purple), transparent);
            top: -10%; left: -8%;
            animation: floatA 14s ease-in-out infinite;
        }
        .float-shape:nth-child(2) {
            width: 300px; height: 300px;
            background: radial-gradient(circle, #b388ff, transparent);
            bottom: -8%; right: -6%;
            animation: floatB 18s ease-in-out infinite;
        }
        .float-shape:nth-child(3) {
            width: 200px; height: 200px;
            background: radial-gradient(circle, var(--purple), transparent);
            top: 40%; left: -4%;
            animation: floatA 16s ease-in-out infinite reverse;
        }
        .float-shape:nth-child(4) {
            width: 150px; height: 150px;
            background: radial-gradient(circle, #d4b3ff, transparent);
            bottom: 20%; right: -3%;
            animation: floatB 12s ease-in-out infinite reverse;
        }
        @keyframes floatA {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -40px) scale(1.05); }
            66% { transform: translate(-20px, 20px) scale(0.95); }
        }
        @keyframes floatB {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(-30px, 30px) scale(1.08); }
            66% { transform: translate(20px, -30px) scale(0.92); }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(140, 82, 255, 0.2);
            border-radius: 28px;
            box-shadow: 0 20px 60px rgba(140, 82, 255, 0.12), 0 8px 20px rgba(140, 82, 255, 0.06), inset 0 1px 0 rgba(255, 255, 255, 0.8);
            overflow: hidden;
            max-width: 460px; width: 100%;
        }

        .forms-wrapper { width: 200%; display: flex; transition: transform 0.6s cubic-bezier(0.645, 0.045, 0.355, 1); }
        #container.show-signup .forms-wrapper { transform: translateX(-50%); }
        .form-section { width: 50%; }

        .pill-indicator {
            display: inline-flex;
            background: rgba(140, 82, 255, 0.1);
            border-radius: 50rem;
            padding: 4px;
            margin-bottom: 1.5rem;
        }
        .pill-indicator button {
            border: none;
            background: transparent;
            padding: 8px 24px;
            border-radius: 50rem;
            font-weight: 600;
            font-size: 13px;
            color: #999;
        }
        .pill-indicator button.active {
            background: var(--purple);
            color: #fff;
            box-shadow: 0 4px 12px var(--purple-glow);
        }

        .input-group-custom {
            border: 2px solid rgba(140, 82, 255, 0.25);
            border-radius: 14px;
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.6);
            transition: border-color 0.3s, box-shadow 0.3s, background 0.3s;
        }
        .input-group-custom:focus-within {
            border-color: var(--purple);
            box-shadow: 0 0 0 4px var(--purple-glow);
            background: #fff;
        }
        .input-group-custom input {
            border: none;
            background: transparent;
            border-radius: 14px;
            outline: none;
            width: 100%;
            padding: 14px 18px;
            font-size: 14px;
        }
        .input-group-custom input::placeholder { color: #b0a0c8; }
        .eye-icon {
            margin: 10px; cursor: pointer; flex-shrink: 0;
            opacity: 0.5; color: var(--purple);
            display: flex; align-items: center;
        }
        .eye-icon:hover { opacity: 1; }

        .toggle-link {
            position: relative;
            color: var(--purple); cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: color 0.2s;
        }
        .toggle-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--purple);
            transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .toggle-link:hover::after { width: 100%; }

        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 100;
            justify-content: center; align-items: center;
        }
        .modal-overlay.show { display: flex; }
        .auth-modal-box {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 2.5rem; border-radius: 24px; text-align: center;
            width: 90%; max-width: 360px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <livewire:toast-message />

    <div class="bg-animated">
        <div class="float-shape"></div>
        <div class="float-shape"></div>
        <div class="float-shape"></div>
        <div class="float-shape"></div>

        <div class="text-center" style="position: absolute; top: 5%; z-index: 2;">
            <img src="{{ asset('images/logo.png') }}" alt="PERFIT" style="height: 48px; margin-bottom: 4px;">
        </div>

        <div class="glass-card" id="container">
            <div class="forms-wrapper">
                <div class="form-section p-4 p-md-5 d-flex flex-column align-items-center">
                    <div class="pill-indicator">
                        <button class="active" disabled>Sign In</button>
                        <button disabled>Sign Up</button>
                    </div>
                    <form id="signinForm" class="w-100">
                        <div class="input-group-custom mb-3">
                            <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="input-group-custom mb-3">
                            <input type="password" name="password" id="signinPass" placeholder="Password" required>
                            <span class="eye-icon" onclick="togglePassword('signinPass', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.667 4.667 -6.667 7 -11 7s-8.333 -2.333 -11 -7c2.667 -4.667 6.667 -7 11 -7s8.333 2.333 11 7"/></svg>
                            </span>
                        </div>
                        <button type="submit" class="btn primary-btn-perfit d-block mx-auto mt-3 px-5">Sign In</button>
                    </form>
                    <div class="w-100 text-center mt-4">
                        <a id="forgotPasswordLink" class="toggle-link">Forgot Password?</a>
                    </div>
                    <div class="mt-4 text-center" style="color: #a0a0b8; font-size: 13px;">
                        Don't have an account?
                        <a id="showSignup" class="toggle-link">Sign Up</a>
                    </div>
                </div>

                <div class="form-section p-4 p-md-5 d-flex flex-column align-items-center">
                    <div class="pill-indicator">
                        <button disabled>Sign In</button>
                        <button class="active" disabled>Sign Up</button>
                    </div>
                    <form id="signupForm" class="w-100">
                        <div class="input-group-custom mb-3">
                            <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="input-group-custom mb-3">
                            <input type="password" name="password" id="signupPass" placeholder="Password" required>
                            <span class="eye-icon" onclick="togglePassword('signupPass', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.667 4.667 -6.667 7 -11 7s-8.333 -2.333 -11 -7c2.667 -4.667 6.667 -7 11 -7s8.333 2.333 11 7"/></svg>
                            </span>
                        </div>
                        <div class="input-group-custom mb-3">
                            <input type="password" id="confirmPass" placeholder="Confirm Password" required>
                            <span class="eye-icon" onclick="togglePassword('confirmPass', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.667 4.667 -6.667 7 -11 7s-8.333 -2.333 -11 -7c2.667 -4.667 6.667 -7 11 -7s8.333 2.333 11 7"/></svg>
                            </span>
                        </div>
                        <button type="submit" class="btn primary-btn-perfit d-block mx-auto mt-3 px-5">Sign Up</button>
                    </form>
                    <div class="mt-4 text-center" style="color: #a0a0b8; font-size: 13px;">
                        Already have an account?
                        <a id="showSignin" class="toggle-link">Sign In</a>
                    </div>
                </div>
            </div>
            <button class="w-100 border-0 py-3 fw-semibold" onclick="window.location.href='/'" style="background: rgba(250,248,255,0.6); color: var(--purple); cursor: pointer; font-size: 13px; border-top: 1px solid rgba(140,82,255,0.1);">Back to Home</button>
        </div>

        <div class="modal-overlay" id="verifyPopup">
            <div class="auth-modal-box">
                <h3 class="fw-bold mb-2" style="color: var(--purple);">Email Verification</h3>
                <p class="text-muted mb-3" style="font-size: 14px;">Enter the 6-digit code sent to your email.</p>
                <input type="text" id="verifyCode" maxlength="6" class="text-center w-75 mx-auto mb-3" style="border: 2px solid var(--purple); border-radius: 10px; font-size: 18px; padding: 12px; letter-spacing: 6px; font-weight: bold; color: var(--purple); background: rgba(255,255,255,0.5); outline: none; display: block;">
                <div class="d-flex justify-content-center gap-3">
                    <button class="btn primary-btn-perfit" onclick="cancelVerification()" style="padding-left: 1.5rem; padding-right: 1.5rem;">Back</button>
                    <button class="btn primary-btn-perfit" onclick="checkVerificationCode()" style="padding-left: 1.5rem; padding-right: 1.5rem;">Verify</button>
                </div>
            </div>
        </div>

        <div class="modal-overlay" id="forgotPopup">
            <div class="auth-modal-box">
                <h3 class="fw-bold mb-2" style="color: var(--purple);">Reset Password</h3>
                <p class="text-muted mb-3" style="font-size: 14px;">Enter your registered email to receive a temporary password.</p>
                <input type="email" id="resetEmail" placeholder="Enter your email" class="w-75 mx-auto mb-3" style="border: 2px solid var(--purple); border-radius: 10px; padding: 12px; text-align: center; background: rgba(255,255,255,0.5); outline: none; display: block;">
                <div class="d-flex justify-content-center gap-3">
                    <button class="btn primary-btn-perfit" onclick="closeForgotPopup()" style="padding-left: 1.5rem; padding-right: 1.5rem;">Back</button>
                    <button class="btn primary-btn-perfit" onclick="sendTemporaryPassword()" style="padding-left: 1.5rem; padding-right: 1.5rem;">Send</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('container');
        const signinForm = document.getElementById('signinForm');
        const signupForm = document.getElementById('signupForm');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function showMessage(text, type) {
            Livewire.dispatch('notify', { text, type });
        }

        function togglePassword(id, el) {
            const input = document.getElementById(id);
            const open = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.667 4.667 -6.667 7 -11 7s-8.333 -2.333 -11 -7c2.667 -4.667 6.667 -7 11 -7s8.333 2.333 11 7"/></svg>';
            const closed = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M0 0h24v24H0z" fill="none"/><path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"/><path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-4.333 0 -8.333 -2.333 -11 -7c1.272 -2.226 2.924 -4.08 4.892 -5.544m4.108 -1.456c5.333 0 9.333 2.333 12 7c-.859 1.504 -1.874 2.887 -3.058 4.117"/><path d="M3 3l18 18"/></svg>';
            if (input.type === 'password') { input.type = 'text'; el.innerHTML = closed; }
            else { input.type = 'password'; el.innerHTML = open; }
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
                method: 'POST', headers: jsonHeaders,
                body: JSON.stringify({ email })
            })
            .then(res => res.json())
            .then(data => {
                if (data.exists) {
                    showMessage('Email already exists. Please use another one.', 'error');
                    return;
                }
                if (pass !== confirm) { showMessage('Passwords do not match!', 'error'); return; }
                if (pass.length < 6) { showMessage('Password must be at least 6 characters.', 'error'); return; }
                showMessage('Processing verification code.', 'success');
                setTimeout(() => {
                    fetch('/admin/send-verification', {
                        method: 'POST', headers: jsonHeaders,
                        body: JSON.stringify({ email })
                    })
                    .then(res => res.json())
                    .then(mailData => {
                        if (mailData.success) {
                            window.tempSignupData = { email, password: pass };
                            document.getElementById('verifyPopup').classList.add('show');
                            showMessage('Verification code sent to your email.', 'success');
                        } else { showMessage(mailData.message, 'error'); }
                    })
                    .catch(() => showMessage('Failed to send verification email.', 'error'));
                }, 1500);
            })
            .catch(() => showMessage('Error checking email.', 'error'));
        });

        function checkVerificationCode() {
            const code = document.getElementById('verifyCode').value.trim();
            fetch('/admin/verify-code', {
                method: 'POST', headers: jsonHeaders,
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
                        } else { showMessage(data.message, 'error'); }
                    })
                    .catch(() => showMessage('Error saving account.', 'error'));
                } else { showMessage(verifyData.message, 'error'); }
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
            if (!email) { showMessage('Please enter your email.', 'error'); return; }
            showMessage('Processing temporary password.', 'success');
            setTimeout(() => {
                fetch('/admin/forgot-password', {
                    method: 'POST', headers: jsonHeaders,
                    body: JSON.stringify({ email })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showMessage('Temporary password sent to your email!', 'success');
                        closeForgotPopup();
                    } else { showMessage(data.message, 'error'); }
                })
                .catch(() => showMessage('Error connecting to server.', 'error'));
            }, 1500);
        }
    </script>

    @livewireScripts
</body>
</html>
