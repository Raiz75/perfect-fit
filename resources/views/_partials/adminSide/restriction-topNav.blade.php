<nav class="admin-nav-tabs">
    <a href="{{ route('admin.restrictions.demographics') }}"
       class="tab-link {{ request()->routeIs('admin.restrictions.demographics') ? 'active' : '' }}">
        <i class="ti ti-users"></i>
        <span>Demographics</span>
    </a>
    <a href="{{ route('admin.restrictions.skills') }}"
       class="tab-link {{ request()->routeIs('admin.restrictions.skills') ? 'active' : '' }}">
        <i class="ti ti-tool"></i>
        <span>Skills</span>
    </a>
</nav>