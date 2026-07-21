<nav class="restrictionTopNav">
    <a href="{{ route('admin.restrictions.demographics') }}"
       class="restrictionTab {{ request()->routeIs('admin.restrictions.demographics') ? 'active' : '' }}">
        <i class="ti ti-users"></i>
        <span>Demographics</span>
    </a>
    <a href="{{ route('admin.restrictions.skills') }}"
       class="restrictionTab {{ request()->routeIs('admin.restrictions.skills') ? 'active' : '' }}">
        <i class="ti ti-tool"></i>
        <span>Skills</span>
    </a>
</nav>
