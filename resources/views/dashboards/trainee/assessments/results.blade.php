@extends('layouts.trainee')

@section('title', 'My Assessment Results')

@section('trainee_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">My Assessment Results</h2>
        <p class="text-muted small mb-0">Track all your completed evaluation attempts and performance metrics.</p>
    </div>
    <a href="{{ route('trainee.assessments') }}" class="btn btn-outline-primary rounded-pill px-4">
        <i class="bi bi-file-earmark-check me-2"></i> Take Assessment
    </a>
</div>

<!-- Summary Metrics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 text-center p-3 bg-white">
            <div class="text-muted small fw-bold text-uppercase mb-1">Total Completed Attempts</div>
            <div class="display-6 fw-bold text-primary">{{ $totalAttempts }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 text-center p-3 bg-white">
            <div class="text-muted small fw-bold text-uppercase mb-1">Passed Assessments</div>
            <div class="display-6 fw-bold text-success">{{ $passedAttempts }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 text-center p-3 bg-white">
            <div class="text-muted small fw-bold text-uppercase mb-1">Average Score</div>
            <div class="display-6 fw-bold text-info">{{ $avgScore }}%</div>
        </div>
    </div>
</div>

<!-- Attempts History Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white py-3 border-0">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="bi bi-clock-history text-primary me-2"></i> Assessment Attempt History
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Course & Assessment</th>
                        <th>Completed Date</th>
                        <th>Score</th>
                        <th>Percentage</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attempts as $attempt)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $attempt->assessment->title ?? 'Certification Assessment' }}</div>
                                <small class="text-muted"><i class="bi bi-journal-bookmark me-1"></i> {{ $attempt->assessment->course->title ?? 'General Course' }}</small>
                            </td>
                            <td>
                                <span class="text-secondary small">{{ $attempt->updated_at ? $attempt->updated_at->format('M d, Y • h:i A') : 'N/A' }}</span>
                            </td>
                            <td>
                                <strong class="text-dark">{{ $attempt->score }} pts</strong>
                            </td>
                            <td>
                                <span class="badge {{ $attempt->passed ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} px-3 py-2 rounded-pill fs-6 fw-bold">
                                    {{ $attempt->percentage }}%
                                </span>
                            </td>
                            <td>
                                @if($attempt->passed)
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i> Passed
                                    </span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3 py-2">
                                        <i class="bi bi-x-circle-fill me-1"></i> Failed
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('trainee.assessments.result', $attempt->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-eye me-1"></i> View Result
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-x display-4 d-block mb-3 text-secondary"></i>
                                <h5 class="fw-bold text-dark">No Assessment Attempts Found</h5>
                                <p class="small mb-3">You have not completed any course assessments yet.</p>
                                <a href="{{ route('trainee.assessments') }}" class="btn btn-primary rounded-pill px-4">
                                    Browse Available Assessments
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
