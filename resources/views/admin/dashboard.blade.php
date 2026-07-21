@extends('_layouts.admin')

@section('title', 'Admin Dashboard - PERFIT')
@section('pageTitle', 'Dashboard')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body text-center p-4">
                    <h2 class="fw-bold mb-1" style="color: #8c52ff;">{{ $totalTakers }}</h2>
                    <p class="text-muted mb-0 small">Total Takers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body text-center p-4">
                    <h2 class="fw-bold mb-1" style="color: #8c52ff;">{{ $todayCount }}</h2>
                    <p class="text-muted mb-0 small">Submissions Today</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body text-center p-4">
                    <h2 class="fw-bold mb-1" style="color: #8c52ff;">{{ Auth::user()->church_code }}</h2>
                    <p class="text-muted mb-0 small">Church Code</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body text-center p-4">
                    <h2 class="fw-bold mb-1" style="color: #8c52ff;">{{ Auth::user()->church_name ?? 'Not set' }}</h2>
                    <p class="text-muted mb-0 small">Church Name</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-3" style="color: #8c52ff;">Filters</h4>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Search Name or Email</label>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Start Date</label>
                    <input type="date" id="startDate" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">End Date</label>
                    <input type="date" id="endDate" class="form-control">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-2">
                    <label class="form-label small text-muted">Gender</label>
                    <select id="genderFilter" class="form-select">
                        <option value="">All Genders</option>
                        <option value="1">Male</option>
                        <option value="2">Female</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Marital Status</label>
                    <select id="maritalFilter" class="form-select">
                        <option value="">All</option>
                        <option value="1">Single</option>
                        <option value="2">Married</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Baptized</label>
                    <select id="baptizedFilter" class="form-select">
                        <option value="">All</option>
                        <option value="1">Yes</option>
                        <option value="2">No</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Time in Faith</label>
                    <select id="faithFilter" class="form-select">
                        <option value="">All</option>
                        <option value="1">1+ Week</option>
                        <option value="2">6+ Months</option>
                        <option value="3">1+ Year</option>
                        <option value="4">2+ Years</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Age</label>
                    <input type="number" id="ageFilter" class="form-control" placeholder="Age">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-12">
                    <label class="form-label small text-muted fw-semibold">Skills</label>
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
                    <label class="form-label small text-muted fw-semibold">Ministry</label>
                    <div class="d-flex flex-wrap gap-3" id="ministryFilterContainer">
                        <!-- Populated by JS -->
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button class="btn primary-btn-perfit btn-sm" id="applyFilterBtn">Apply Filters</button>
                <button class="btn btn-outline-secondary btn-sm" id="resetFilterBtn" style="border-radius: 50rem;">Reset</button>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-3 text-center" style="color: #8c52ff;">Volunteer Metrics</h4>
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
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-3" style="color: #8c52ff;">Volunteer Assessment Reports</h4>
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0" id="userReportTable" style="font-size: 13px;">
                    <thead class="table-light" style="position: sticky; top: 0; z-index: 2;">
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
    @vite(['resources/js/admin-dashboard.js'])
@endpush