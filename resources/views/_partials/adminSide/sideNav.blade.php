<aside class="adminSidebar" id="adminSidebar">
    <div class="sidebarHeader">
        <img src="{{ asset('images/icn-logo.png') }}" alt="PERFIT" class="sidebarLogo">
        <span class="sidebarBrandText">Perfit</span>
    </div>

    <nav class="sidebarNav">
        <a href="{{ route('admin.dashboard') }}"
           class="sidebarLink {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="ti ti-layout-dashboard sidebarIcon"></i>
            <span class="sidebarLabel">Dashboard</span>
        </a>

        <a href="{{ route('admin.restrictions') }}"
           class="sidebarLink {{ request()->routeIs('admin.restrictions*') ? 'active' : '' }}">
            <i class="ti ti-adjustments-alt sidebarIcon"></i>
            <span class="sidebarLabel">Restriction Editor</span>
        </a>

        <a href="{{ route('admin.questions') }}"
           class="sidebarLink {{ request()->routeIs('admin.questions*') ? 'active' : '' }}">
            <i class="ti ti-help-hexagon sidebarIcon"></i>
            <span class="sidebarLabel">Question Editor</span>
        </a>

        <a href="{{ route('admin.settings') }}"
           class="sidebarLink {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <i class="ti ti-settings sidebarIcon"></i>
            <span class="sidebarLabel">Settings</span>
        </a>
    </nav>

    <div class="sidebarFooter">
        <button type="button" class="sidebarLink logoutLink" onclick="document.getElementById('logoutModal').classList.add('show')">
            <i class="ti ti-logout sidebarIcon"></i>
            <span class="sidebarLabel">Logout</span>
        </button>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="logout-modal-overlay" id="logoutModal">
        <div class="logout-modal-box">
            <div class="logout-modal-icon">
                <i class="ti ti-logout"></i>
            </div>
            <h3 class="logout-modal-title">Confirm Logout</h3>
            <p class="logout-modal-text">Are you sure you want to log out?</p>
            <div class="logout-modal-actions">
                <button class="btn btn-secondary logout-btn-cancel" onclick="document.getElementById('logoutModal').classList.remove('show')">Cancel</button>
                <form method="POST" action="{{ route('admin.logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn primary-btn-perfit">Yes, Logout</button>
                </form>
            </div>
        </div>
    </div>

    <button class="sidebarToggle" id="sidebarToggle" title="Toggle sidebar">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </button>
</aside>
