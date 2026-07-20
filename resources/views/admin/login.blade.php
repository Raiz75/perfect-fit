<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/icn-logo.png') }}">
    <title>PERFIT - Admin Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --purple: #8c52ff; --light-purple: #f0e6ff; }
        body {
            font-family: 'Poppins', Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            margin: 0;
            background: linear-gradient(135deg, #faf8ff 0%, #f0e6ff 100%);
        }
        .title {
            position: absolute;
            top: 6%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--purple);
            width: 100%;
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
        }
        .container {
            position: relative;
            width: 100%;
            max-width: 450px;
            background: #fff;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(140, 82, 255, 0.15);
            border: 1px solid var(--purple);
        }
        .forms-wrapper {
            position: relative;
            width: 200%;
            display: flex;
            transition: transform 0.6s cubic-bezier(0.645, 0.045, 0.355, 1);
        }
        .container.show-signup .forms-wrapper { transform: translateX(-50%); }
        .form-section {
            width: 50%;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        h2 {
            color: var(--purple);
            margin-bottom: 24px;
            font-size: 28px;
            font-weight: 700;
            width: 100%;
            text-align: center;
        }
        .input-group {
            width: 100%;
            margin-bottom: 16px;
            position: relative;
            display: flex;
            border: 2px solid var(--purple);
            background-color: white;
            border-radius: 12px;
            align-items: center;
        }
        .input-group input {
            width: 100%;
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 14px;
            border: 2px solid transparent;
            background: #f8f9fa;
            outline: none;
        }
        .input-group input:focus { border-color: var(--purple) !important; }
        .input-group img {
            height: 28px;
            width: 28px;
            margin: 10px;
            cursor: pointer;
            flex-shrink: 0;
        }
        button[type="submit"] {
            width: 120px;
            height: 44px;
            border: 3px solid var(--purple);
            border-radius: 12px;
            font-size: 1.1rem;
            background: var(--purple);
            color: white;
            margin: 16px auto 0;
            display: block;
            cursor: pointer;
            transition: .3s ease-in-out;
        }
        button[type="submit"]:hover { background: #f0e6ff; color: var(--purple); }
        .toggle-link {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: var(--purple);
        }
        .toggle-link a {
            color: var(--purple);
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }
        .toggle-link a:hover { text-decoration: underline; }
        .forgot-password {
            width: 100%;
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
            padding-top: 10px;
        }
        .forgot-password a {
            color: var(--purple);
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }
        .forgot-password a:hover { text-decoration: underline; }
        .gotoHome {
            display: block;
            width: 100%;
            padding: 12px;
            border: none;
            border-top: 1px solid #eee;
            background: #faf8ff;
            color: var(--purple);
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: .2s;
        }
        .gotoHome:hover { background: var(--light-purple); }
        .message {
            position: fixed;
            top: 30px;
            right: 30px;
            width: 300px;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 14px;
            opacity: 0;
            transition: 0.4s ease-in-out;
            z-index: 105;
            pointer-events: none;
        }
        .message.success { background: #d4edda; color: #155724; border: 2px solid #155724; }
        .message.error { background: #f8d7da; color: #721c24; border: 2px solid #721c24; }
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(6px);
            z-index: 100;
            justify-content: center;
            align-items: center;
        }
        .modal-overlay.show { display: flex; }
        .modal-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            width: 90%;
            max-width: 360px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            color: var(--purple);
        }
        .modal-box h3 { margin-bottom: 12px; font-weight: 700; }
        .modal-box p { margin-bottom: 20px; font-size: 14px; color: #666; }
        .modal-box input {
            text-align: center;
            font-size: 18px;
            border: 2px solid var(--purple);
            border-radius: 8px;
            padding: 10px;
            width: 80%;
            outline: none;
        }
        .btn-row {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 20px;
        }
        .btn-row button {
            padding: 10px 24px;
            border: 2px solid var(--purple);
            border-radius: 10px;
            font-size: 14px;
            background: var(--purple);
            color: white;
            cursor: pointer;
            transition: .3s;
        }
        .btn-row button:hover { background: var(--light-purple); color: var(--purple); }
        .btn-row button.secondary { background: #fff; color: var(--purple); }
        .btn-row button.secondary:hover { background: #f5f5f5; }
        @media (max-width: 480px) {
            .form-section { padding: 30px 20px; }
            h2 { font-size: 22px; }
            .title { font-size: 1.8rem; top: 4%; }
        }
    </style>
</head>
<body>
    <h1 class="title">Admin Login</h1>

    <div class="container" id="container">
        <div class="forms-wrapper">
            <div class="form-section" id="signinSection">
                <h2>Welcome Back</h2>
                <form id="signinForm">
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" id="signinPass" placeholder="Password" required>
                        <img src="{{ asset('images/icn-closedEyes.png') }}" alt="toggle" onclick="togglePassword('signinPass', this)">
                    </div>
                    <button type="submit">Sign In</button>
                </form>
                <div class="forgot-password">
                    <a id="forgotPasswordLink">Forgot Password?</a>
                </div>
                <div class="toggle-link">
                    Don't have an account? <a id="showSignup">Sign Up</a>
                </div>
            </div>
            <div class="form-section" id="signupSection">
                <h2>Create Account</h2>
                <form id="signupForm">
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" id="signupPass" placeholder="Password" required>
                        <img src="{{ asset('images/icn-closedEyes.png') }}" alt="toggle" onclick="togglePassword('signupPass', this)">
                    </div>
                    <div class="input-group">
                        <input type="password" id="confirmPass" placeholder="Confirm Password" required>
                        <img src="{{ asset('images/icn-closedEyes.png') }}" alt="toggle" onclick="togglePassword('confirmPass', this)">
                    </div>
                    <button type="submit">Sign Up</button>
                </form>
                <div class="toggle-link">
                    Already have an account? <a id="showSignin">Sign In</a>
                </div>
            </div>
        </div>
        <button class="gotoHome" onclick="goHome()">Go Home</button>
    </div>

    <div class="message" id="message"></div>

    <div class="modal-overlay" id="verifyPopup">
        <div class="modal-box">
            <h3>Email Verification</h3>
            <p>Enter the 6-digit code sent to your email.</p>
            <input type="text" id="verifyCode" maxlength="6">
            <div class="btn-row">
                <button class="secondary" onclick="cancelVerification()">Back</button>
                <button onclick="checkVerificationCode()">Verify</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="forgotPopup">
        <div class="modal-box">
            <h3>Reset Password</h3>
            <p>Enter your registered email to receive a temporary password.</p>
            <input type="email" id="resetEmail" placeholder="Enter your email">
            <div class="btn-row">
                <button class="secondary" onclick="closeForgotPopup()">Back</button>
                <button onclick="sendTemporaryPassword()">Send</button>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('container');
        const signinForm = document.getElementById('signinForm');
        const signupForm = document.getElementById('signupForm');
        const message = document.getElementById('message');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function showMessage(text, type) {
            message.textContent = text;
            message.className = 'message ' + type;
            message.style.opacity = '1';
            setTimeout(() => { message.style.opacity = '0'; }, 4000);
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
</body>
</html>
