<nav class="admin-nav-tabs">
    <a href="{{ route('admin.questions.skill') }}"
       class="tab-link {{ request()->routeIs('admin.questions.skill') ? 'active' : '' }}">
        <i class="ti ti-tool"></i>
        <span>Skill</span>
    </a>
    <a href="{{ route('admin.questions.interest') }}"
       class="tab-link {{ request()->routeIs('admin.questions.interest') ? 'active' : '' }}">
        <i class="ti ti-heart"></i>
        <span>Interest & Passion</span>
    </a>
    <a href="{{ route('admin.questions.behavioral') }}"
       class="tab-link {{ request()->routeIs('admin.questions.behavioral') ? 'active' : '' }}">
        <i class="ti ti-users"></i>
        <span>Behavioral</span>
    </a>
</nav>