/**
 * Admin Login — Toggle Password Visibility
 */
window.togglePassword = function (id, el) {
    const input = document.getElementById(id);
    const open = '<i class="ti ti-eye-off"></i>';
    const closed = '<i class="ti ti-eye"></i>';
    if (input.type === 'password') { input.type = 'text'; el.innerHTML = closed; }
    else { input.type = 'password'; el.innerHTML = open; }
};

document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('container');
    const signinForm = document.getElementById('signinForm');
    const signupForm = document.getElementById('signupForm');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const jsonHeaders = { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken };

    function showMessage(text, type) {
        Livewire.dispatch('notify', { text, type });
    }

    // Tab switching
    document.getElementById('showSignup')?.addEventListener('click', () => container.classList.add('show-signup'));
    document.getElementById('showSignin')?.addEventListener('click', () => container.classList.remove('show-signup'));

    // Sign In
    signinForm?.addEventListener('submit', (e) => {
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

    // Sign Up
    signupForm?.addEventListener('submit', (e) => {
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
            if (pass.length < 8) { showMessage('Password must be at least 8 characters.', 'error'); return; }
            if (!/[A-Z]/.test(pass)) { showMessage('Password must contain at least 1 uppercase letter.', 'error'); return; }
            if (!/[0-9]/.test(pass)) { showMessage('Password must contain at least 1 number.', 'error'); return; }
            if (!/[^a-zA-Z0-9]/.test(pass)) { showMessage('Password must contain at least 1 special character.', 'error'); return; }
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

    // Verification code
    window.checkVerificationCode = function () {
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
    };

    window.cancelVerification = function () {
        document.getElementById('verifyPopup').classList.remove('show');
        showMessage('Verification cancelled.', 'error');
    };

    // Forgot Password
    document.getElementById('forgotPasswordLink')?.addEventListener('click', () => {
        document.getElementById('forgotPopup').classList.add('show');
    });

    window.closeForgotPopup = function () {
        document.getElementById('forgotPopup').classList.remove('show');
        document.getElementById('resetEmail').value = '';
    };

    window.sendTemporaryPassword = function () {
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
    };
});