// Admin Sidebar Toggle
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('adminSidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const hamburger = document.getElementById('mobileHamburger');
    const overlay = document.getElementById('sidebarOverlay');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
        });
    }

    if (hamburger && sidebar && overlay) {
        hamburger.addEventListener('click', function () {
            sidebar.classList.toggle('mobileOpen');
            overlay.classList.toggle('show');
        });

        overlay.addEventListener('click', function () {
            sidebar.classList.remove('mobileOpen');
            overlay.classList.remove('show');
        });
    }

    // Admin@admin special user — hide "Reset to default" buttons
    const userEmailMeta = document.querySelector('meta[name="user-email"]');
    if (userEmailMeta && userEmailMeta.content === 'admin@admin') {
        document.querySelectorAll('.noToAdmin').forEach(function (el) {
            el.style.display = 'none';
        });
    }
});