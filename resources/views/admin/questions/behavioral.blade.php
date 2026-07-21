@extends('_layouts.admin')

@section('title', 'Behavioral Questions - PERFIT')
@section('pageTitle', 'Question Editor')

@section('content')
    @include('_partials.adminSide.question-topNav')

    <div class="card border-0 shadow-sm mt-3" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3 gap-2">
                <div>
                    <h5 class="fw-semibold mb-1">Behavioral Questions</h5>
                    <p class="text-muted small mb-0">
                        Edit the behavioral questions to assess volunteer <strong>traits</strong> and <strong>ministry fit</strong>.
                        Click on a cell to edit the text directly.
                    </p>
                </div>
                <div class="d-flex gap-2 flex-shrink-0">
                    <button class="noToAdmin btn btn-outline-secondary btn-sm">Reset all to default</button>
                    <button class="btn btn-primary btn-sm">Save all changes</button>
                </div>
            </div>
            <div style="overflow-x: auto; max-width: 100%; -webkit-overflow-scrolling: touch;">
                <table class="table table-bordered table-hover align-middle mb-0" id="behavioralQuestions" style="width: 100%; min-width: 650px;">
                    <thead class="table-light">
                        <tr>
                            <th>Ministry</th>
                            <th style="width: 140px;">Question Number</th>
                            <th>English Question</th>
                            <th>Tagalog Question</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Loading questions...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
