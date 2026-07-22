@extends('_layouts.admin')

@section('title', 'Admin Dashboard - PERFIT')
@section('pageTitle', 'Dashboard')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="admin-glass-stat">
                <i class="ti ti-users stat-icon"></i>
                <div class="stat-number">{{ $totalTakers }}</div>
                <div class="stat-label">Total Takers</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-glass-stat">
                <i class="ti ti-clipboard-check stat-icon"></i>
                <div class="stat-number">{{ $todayCount }}</div>
                <div class="stat-label">Submissions Today</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-glass-stat" style="position:relative;">
                <i class="ti ti-key stat-icon"></i>
                <div class="stat-number" id="churchCodeDisplay" style="cursor:pointer;" onclick="copyChurchCode()">
                    {{ Auth::user()->church_code }}
                    <i class="ti ti-copy" id="copyIcon" style="font-size:1rem;margin-left:6px;opacity:0.5;vertical-align:middle;"></i>
                </div>
                <div class="stat-label">Church Code</div>
                <span id="copyFeedback" style="position:absolute;bottom:8px;left:50%;transform:translateX(-50%);font-size:10px;color:#28a745;font-weight:600;opacity:0;transition:opacity 0.3s;">Copied!</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-glass-stat">
                <i class="ti ti-building-church stat-icon"></i>
                <div class="stat-number">{{ Auth::user()->church_name ?? 'Not set' }}</div>
                <div class="stat-label">Church Name</div>
            </div>
        </div>
    </div>

    <div class="admin-glass-filters mb-4">
        <div class="filter-title">
            <i class="ti ti-adjustments-horizontal me-1"></i> Filters
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label">Search Name or Email</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Search...">
            </div>
            <div class="col-md-4">
                <label class="form-label">Start Date</label>
                <input type="date" id="startDate" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">End Date</label>
                <input type="date" id="endDate" class="form-control">
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-2">
                <label class="form-label">Gender</label>
                <select id="genderFilter" class="form-select">
                    <option value="">All Genders</option>
                    <option value="1">Male</option>
                    <option value="2">Female</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Marital Status</label>
                <select id="maritalFilter" class="form-select">
                    <option value="">All</option>
                    <option value="1">Single</option>
                    <option value="2">Married</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Baptized</label>
                <select id="baptizedFilter" class="form-select">
                    <option value="">All</option>
                    <option value="1">Yes</option>
                    <option value="2">No</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Time in Faith</label>
                <select id="faithFilter" class="form-select">
                    <option value="">All</option>
                    <option value="1">1+ Week</option>
                    <option value="2">6+ Months</option>
                    <option value="3">1+ Year</option>
                    <option value="4">2+ Years</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Age</label>
                <input type="number" id="ageFilter" class="form-control" placeholder="Age">
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12">
                <label class="form-label">Skills</label>
                <div class="d-flex flex-wrap gap-3">
                    @foreach(['music' => 'Music', 'technology' => 'Technology', 'writing' => 'Writing', 'technical' => 'Technical', 'speaking' => 'Speaking', 'accounting' => 'Accounting', 'mentoring' => 'Mentoring', 'bible_knowledge' => 'Bible Knowledge'] as $key => $label)
                    <div class="form-check">
                        <input class="form-check-input skill-filter" type="checkbox" value="{{ $key }}" id="skill_{{ $key }}">
                        <label class="form-check-label small" for="skill_{{ $key }}">{{ $label }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12">
                <label class="form-label">Ministry</label>
                <div class="d-flex flex-wrap gap-3" id="ministryFilterContainer">
                    @foreach($ministries as $ministry)
                    <div class="form-check">
                        <input class="form-check-input ministry-filter" type="checkbox" value="{{ $ministry }}" id="min_{{ Str::slug($ministry, '_') }}">
                        <label class="form-check-label small" for="min_{{ Str::slug($ministry, '_') }}">{{ $ministry }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button class="btn primary-btn-perfit btn-sm" id="applyFilterBtn">Apply Filters</button>
            <button class="btn btn-outline-secondary btn-sm" id="resetFilterBtn">Reset</button>
        </div>
    </div>

    <div class="admin-glass-card p-4 mb-4">
        <h4 class="admin-section-title text-center mb-4">Volunteer Metrics</h4>
        <div class="row g-4">
            <div class="col-md-6"><div class="chart-container"><canvas id="genderChart" height="250"></canvas></div></div>
            <div class="col-md-6"><div class="chart-container"><canvas id="ministryChart" height="250"></canvas></div></div>
            <div class="col-md-6"><div class="chart-container"><canvas id="skillsChart" height="250"></canvas></div></div>
            <div class="col-md-6"><div class="chart-container"><canvas id="faithChart" height="250"></canvas></div></div>
            <div class="col-md-6"><div class="chart-container"><canvas id="ageChart" height="250"></canvas></div></div>
            <div class="col-md-6"><div class="chart-container"><canvas id="baptizedChart" height="250"></canvas></div></div>
            <div class="col-md-6 mx-auto"><div class="chart-container"><canvas id="maritalChart" height="250"></canvas></div></div>
        </div>
    </div>

    <div class="admin-glass-card p-4">
        <h4 class="admin-section-title mb-4">Volunteer Assessment Reports</h4>
        <div class="admin-glass-table">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table align-middle mb-0" id="userReportTable" style="font-size: 13px;">
                    <thead style="position: sticky; top: 0; z-index: 2;">
                        <tr>
                            <th>Date</th>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Skills</th>
                            <th>Eligible Ministry</th>
                            <th>Gender</th>
                            <th>Age</th>
                            <th>Marital</th>
                            <th>Baptized</th>
                            <th>Faith</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyChurchCode() {
            var code = document.getElementById('churchCodeDisplay').childNodes[0].textContent.trim();
            navigator.clipboard.writeText(code).then(function() {
                var fb = document.getElementById('copyFeedback');
                fb.style.opacity = '1';
                setTimeout(function() { fb.style.opacity = '0'; }, 1500);
            });
        }
    </script>
    @vite(['resources/js/admin-dashboard.js'])
@endpush