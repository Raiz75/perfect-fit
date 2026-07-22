window.togglePassword = function (id, el) {
    const input = document.getElementById(id);
    const open = '<i class="ti ti-eye"></i>';
    const closed = '<i class="ti ti-eye-off"></i>';
    if (input.type === 'password') { input.type = 'text'; el.innerHTML = closed; }
    else { input.type = 'password'; el.innerHTML = open; }
};

document.addEventListener('DOMContentLoaded', function () {
    const toastData = document.getElementById('toast-data');
    if (toastData) {
        window.dispatchEvent(new CustomEvent('notify', {
            detail: { text: toastData.dataset.message, type: toastData.dataset.type }
        }));
    }

    document.getElementById('forgotPasswordLink')?.addEventListener('click', () => {
        document.getElementById('forgotPopup').classList.add('show');
    });

    if (window.location.search.includes('verify=')) {
        document.getElementById('verifyPopup')?.classList.add('show');
    }
});
