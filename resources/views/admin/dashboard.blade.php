@extends('_layouts.admin')

@section('title', 'Admin Dashboard - PERFIT')

@section('content')
    <?php $user = Auth::user(); ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold" style="color: #1a1a2e;">Dashboard</h1>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body text-center p-4">
                    <h2 class="fw-bold mb-1" style="color: #8c52ff;">{{ \App\Models\DemographicRestriction::where('user_id', $user->id)->count() }}</h2>
                    <p class="text-muted mb-0 small">Restrictions Set</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body text-center p-4">
                    <h2 class="fw-bold mb-1" style="color: #8c52ff;">{{ \App\Models\UserReport::where('church_code', $user->church_code)->count() }}</h2>
                    <p class="text-muted mb-0 small">Submissions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body text-center p-4">
                    <h2 class="fw-bold mb-1" style="color: #8c52ff;">{{ $user->church_code }}</h2>
                    <p class="text-muted mb-0 small">Church Code</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body text-center p-4">
                    <h2 class="fw-bold mb-1" style="color: #8c52ff;">{{ $user->church_name ?? 'Not set' }}</h2>
                    <p class="text-muted mb-0 small">Church Name</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4 text-center text-muted py-5">
            <p class="mb-0">Full admin panel coming in Phase 3.</p>
        </div>
    </div>
@endsection


