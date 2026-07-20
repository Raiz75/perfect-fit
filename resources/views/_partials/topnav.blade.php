<nav class="navbar navbar-expand-md fixed-top py-2" style="background: rgba(255,255,255,.92); backdrop-filter: blur(10px); border-bottom: 1px solid #eee3fc;">
    <div class="container">
        <a class="navbar-brand py-0" href="/">
            <img src="{{ asset('images/logo.png') }}" alt="Perfit Logo" class="navbar-brand-img" style="height: 56px;">
        </a>

        <button class="navbar-toggler border-0 collapsed" type="button" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation" style="color: #8c52ff;" id="navbarToggler">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto align-items-center gap-1 gap-md-2">
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-pill nav-scroll" href="#home" style="color: #1a1a2e; font-weight: 500; transition: all .2s;">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-pill nav-scroll" href="#how-it-works" style="color: #1a1a2e; font-weight: 500; transition: all .2s;">How it Works</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-pill nav-scroll" href="#ministries" style="color: #1a1a2e; font-weight: 500; transition: all .2s;">Ministries</a>
                </li>
                <li class="nav-item ms-md-2">
                    <a class="btn primary-btn-perfit" onclick="openModal('overlayUser')">
                        Take Assessment
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.navbar .nav-link:hover { background: #f5eeff; color: #8c52ff !important; }
.navbar .nav-link:focus-visible { box-shadow: none; }
</style>
