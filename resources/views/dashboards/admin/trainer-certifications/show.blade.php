@extends('layouts.admin')

@section('admin_content')
<div class="mb-4">
    <a href="{{ route('admin.trainer-certifications.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Back to Trainer Eligibility List
    </a>
    <div class="d-flex align-items-center justify-content-between mt-2">
        <div>
            <h2 class="fw-bold mb-1">Trainer Detail: {{ $trainer->name }}</h2>
            <p class="text-muted">{{ $trainer->email }} | Department: {{ $profile->department ?? 'N/A' }}</p>
        </div>
        <div>
            @if($isEligible)
                <span class="badge bg-success-subtle text-success fs-6 px-3 py-2 border border-success-subtle rounded-pill">
                    <i class="bi bi-shield-check me-1"></i> Eligible for Certification (100%)
                </span>
            @else
                <span class="badge bg-warning-subtle text-warning fs-6 px-3 py-2 border border-warning-subtle rounded-pill">
                    <i class="bi bi-exclamation-triangle me-1"></i> Profile Incomplete ({{ $completionPercentage }}%)
                </span>
            @endif
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4 mb-5">
    <!-- Profile Completion Status -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold text-primary mb-3">
                    <i class="bi bi-person-bounding-box me-2"></i> Profile Completion Audit
                </h5>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small">Calculated Completion</span>
                        <strong class="text-dark">{{ $completionPercentage }}%</strong>
                    </div>
                    <div class="progress rounded-pill" style="height: 10px;">
                        <div class="progress-bar {{ $isEligible ? 'bg-success' : 'bg-warning' }}" role="progressbar" style="width: {{ $completionPercentage }}%;"></div>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="fw-bold mb-2">Required Verification Fields:</h6>
                <ul class="list-group list-group-flush">
                    @php
                        $fieldLabels = [
                            'photo' => 'Profile Photo',
                            'qualification' => 'Qualifications',
                            'experience' => 'Experience Details',
                            'expertise' => 'Areas of Expertise',
                            'department' => 'Department Name',
                            'bio' => 'Professional Bio',
                            'subjects' => 'Subjects Handled',
                        ];
                    @endphp
                    @foreach($fieldLabels as $field => $label)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                            <span>{{ $label }}</span>
                            @if(!empty($profile->{$field}))
                                <span class="badge bg-success-subtle text-success rounded-pill px-3"><i class="bi bi-check me-1"></i> Filled</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-3"><i class="bi bi-x me-1"></i> Missing</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Assigned Certifications & Status -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-primary mb-0">
                        <i class="bi bi-award-fill me-2"></i> Assigned Course Certifications
                    </h5>
                    @if($isEligible)
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#assignModalShow">
                            <i class="bi bi-plus-circle me-1"></i> Assign New Course
                        </button>
                    @endif
                </div>

                @if($certifications->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($certifications as $cert)
                            <div class="list-group-item px-0 py-3 bg-transparent border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">{{ $cert->course->title }}</h6>
                                        <small class="text-muted d-block">Department: {{ $cert->course->category->name ?? 'General' }}</small>
                                        @if($cert->issued_at)
                                            <small class="text-muted me-3">Issued: {{ $cert->issued_at->format('M d, Y') }}</small>
                                            <small class="text-danger">Expires: {{ $cert->expires_at ? $cert->expires_at->format('M d, Y') : 'N/A' }}</small>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <span class="badge {{ $cert->badge_class }} px-3 py-1 rounded-pill mb-2 d-inline-block">
                                            {{ $cert->status_label }}
                                        </span>
                                        @if($cert->score !== null)
                                            <div class="fw-bold {{ $cert->score >= 80 ? 'text-success' : 'text-danger' }}">
                                                Score: {{ $cert->score }}%
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-light text-center py-4 mb-0 rounded-3 text-muted">
                        No certifications assigned to this trainer yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal for Assigning Course Assessment in Show View -->
@if($isEligible)
    <div class="modal fade" id="assignModalShow" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <form method="POST" action="{{ route('admin.trainer-certifications.assign') }}">
                    @csrf
                    <input type="hidden" name="trainer_id" value="{{ $trainer->id }}">
                    
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-header-title fw-bold">Assign Certification Scenario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted small">Assign a course scenario assessment to <strong>{{ $trainer->name }}</strong>.</p>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Course</label>
                            <select class="form-select" name="course_id" required>
                                <option value="">-- Choose Course --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Assign Assessment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<!-- Comprehensive Attempts History Table -->
<div class="card border-0 shadow-sm rounded-4 mb-5">
    <div class="card-body p-4">
        <h5 class="fw-bold text-primary mb-4">
            <i class="bi bi-clock-history me-2"></i> Trainer Attempt History Audit Log
        </h5>

        @if($attempts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Course</th>
                            <th>Attempt Type</th>
                            <th>Score</th>
                            <th>Result</th>
                            <th>Attempt Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attempts as $attempt)
                            <tr>
                                <td><strong class="text-dark">{{ $attempt->certification->course->title ?? 'N/A' }}</strong></td>
                                <td>
                                    @if($attempt->attempt_type === 'initial')
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3">Initial Assessment</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-dark rounded-pill px-3">Renewal Assessment</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold {{ $attempt->score >= 80 ? 'text-success' : 'text-danger' }}">
                                        {{ $attempt->score }}%
                                    </span>
                                </td>
                                <td>
                                    @if($attempt->passed)
                                        <span class="badge bg-success rounded-pill px-3">Passed</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill px-3">Failed</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $attempt->created_at->format('M d, Y - h:i A') }}</small></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-light border border-light-subtle text-center py-4 mb-0 rounded-3 text-muted">
                No attempt logs found for this trainer.
            </div>
        @endif
    </div>
</div>
@endsection
