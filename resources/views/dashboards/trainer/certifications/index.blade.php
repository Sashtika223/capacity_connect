@extends('layouts.trainer')

@section('trainer_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Trainer Certifications & Eligibility</h2>
        <p class="text-muted mb-0">Manage course teaching eligibility, scenario assessments, and renewal credentials.</p>
    </div>
    <div>
        @if($isProfileComplete)
            <span class="badge bg-success-subtle text-success fs-6 px-3 py-2 border border-success-subtle rounded-pill">
                <i class="bi bi-shield-check me-1"></i> Eligible for Certification
            </span>
        @else
            <span class="badge bg-danger-subtle text-danger fs-6 px-3 py-2 border border-danger-subtle rounded-pill">
                <i class="bi bi-exclamation-octagon me-1"></i> Profile Incomplete ({{ $completionPercentage }}%)
            </span>
        @endif
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(!$isProfileComplete)
    <div class="card border-danger shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 text-center">
            <div class="text-danger mb-3">
                <i class="bi bi-lock-fill display-4"></i>
            </div>
            <h4 class="fw-bold text-danger">Certification Workflow Locked</h4>
            <p class="text-muted mb-3">Your profile is currently at <strong>{{ $completionPercentage }}%</strong>. Complete all required profile fields to enable certification testing and course teaching eligibility.</p>
            <a href="{{ route('trainer.profile') }}" class="btn btn-danger rounded-pill px-4">
                <i class="bi bi-person-fill-gear me-2"></i> Complete Profile Now
            </a>
        </div>
    </div>
@endif

<div class="row g-4 mb-5">
    <div class="col-12">
        <h4 class="fw-bold mb-3 text-primary">Assigned Certifications</h4>
    </div>

    @forelse($certifications as $cert)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden">
                <div class="card-header bg-light border-0 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-secondary-subtle text-dark rounded-pill">
                            <i class="bi bi-book me-1"></i> Course Certification
                        </span>
                        <span class="badge {{ $cert->badge_class }} px-3 py-1 rounded-pill fw-bold">
                            {{ $cert->status_label }}
                        </span>
                    </div>
                    <h5 class="fw-bold text-dark mb-0">{{ $cert->course->title }}</h5>
                </div>
                <div class="card-body p-4 d-flex flex-column">
                    <div class="mb-3">
                        <small class="text-muted d-block">Department</small>
                        <span class="fw-medium text-dark">{{ $cert->course->category->name ?? 'General Capacity' }}</span>
                    </div>

                    @if($cert->issued_at)
                        <div class="row g-2 mb-3 bg-light p-3 rounded-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Issued Date</small>
                                <span class="fw-bold text-dark small">{{ $cert->issued_at->format('M d, Y') }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Expires Date</small>
                                <span class="fw-bold text-danger small">{{ $cert->expires_at ? $cert->expires_at->format('M d, Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    @endif

                    @if($cert->score !== null)
                        <div class="mb-3 bg-light p-3 rounded-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-muted small">Latest Test Score</span>
                                <span class="fs-6 fw-bold {{ $cert->score >= 80 ? 'text-success' : ($cert->score >= 60 ? 'text-warning-emphasis' : 'text-danger') }}">
                                    {{ number_format($cert->score, 2) }}%
                                </span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted small">Digital Profile Level</span>
                                <span class="badge {{ $cert->digital_profile_badge_class }} rounded-pill px-3 py-1 fw-bold">
                                    <i class="bi bi-award-fill me-1"></i> {{ $cert->digital_profile_level }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="mt-auto">
                        @if(in_array($cert->calculated_status, ['assigned', 'certification_assigned', 'pending', 'certification_pending']) || in_array($cert->status, ['assigned', 'certification_assigned', 'pending', 'certification_pending']))
                            <a href="{{ route('trainer.certifications.test', $cert->id) }}" class="btn btn-primary w-100 rounded-pill fw-bold">
                                <i class="bi bi-play-circle-fill me-2"></i> Start Scenario Assessment
                            </a>
                        @elseif(in_array($cert->calculated_status, ['renewal_required', 'certification_expired', 'expired', 'renewal_failed']) || in_array($cert->status, ['renewal_required', 'certification_expired', 'expired', 'renewal_failed']))
                            <a href="{{ route('trainer.certifications.renewal', $cert->id) }}" class="btn btn-warning text-dark w-100 rounded-pill fw-bold mb-2">
                                <i class="bi bi-arrow-repeat me-2"></i> Take Renewal Test
                            </a>
                            <a href="{{ route('trainer.certifications.show', $cert->id) }}" class="btn btn-outline-secondary w-100 rounded-pill btn-sm">
                                <i class="bi bi-file-earmark-text me-1"></i> View Result Breakdown
                            </a>
                        @elseif(in_array($cert->calculated_status, ['certified', 'renewal_passed']) || in_array($cert->status, ['certified', 'renewal_passed']))
                            <div class="d-flex flex-column gap-2">
                                <button class="btn btn-outline-success w-100 rounded-pill" disabled>
                                    <i class="bi bi-patch-check-fill me-1"></i> Active Trainer
                                </button>
                                <a href="{{ route('trainer.certifications.show', $cert->id) }}" class="btn btn-outline-primary w-100 rounded-pill btn-sm fw-semibold">
                                    <i class="bi bi-file-earmark-text me-1"></i> View Result Breakdown
                                </a>
                            </div>
                        @else
                            <div class="d-flex flex-column gap-2">
                                <button class="btn btn-secondary w-100 rounded-pill" disabled>
                                    {{ $cert->status_label }}
                                </button>
                                <a href="{{ route('trainer.certifications.show', $cert->id) }}" class="btn btn-outline-primary w-100 rounded-pill btn-sm fw-semibold">
                                    <i class="bi bi-file-earmark-text me-1"></i> View Result Breakdown
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted">
                <i class="bi bi-award fs-1 d-block mb-3 text-secondary"></i>
                <h5>No Certifications Assigned Yet</h5>
                <p class="mb-0">Administrators will assign course scenario assessments once your profile is verified and 100% complete.</p>
            </div>
        </div>
    @endforelse
</div>

<!-- Complete Certification Attempt History Table -->
<div class="card border-0 shadow-sm rounded-4 mb-5">
    <div class="card-body p-4">
        <h5 class="fw-bold text-primary mb-4">
            <i class="bi bi-clock-history me-2"></i> Certification Attempt History
        </h5>

        @if($attempts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Course</th>
                            <th>Attempt Type</th>
                            <th>Score</th>
                            <th>Result Status</th>
                            <th>Digital Profile Level</th>
                            <th>Attempt Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attempts as $attempt)
                            <tr>
                                <td>
                                    <strong class="text-dark">{{ $attempt->certification->course->title ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-3 py-2 fw-semibold">
                                        Certification Assessment
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold fs-6 {{ $attempt->score >= 80 ? 'text-success' : ($attempt->score >= 60 ? 'text-warning-emphasis' : 'text-danger') }}">
                                        {{ number_format($attempt->score, 2) }}%
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle rounded-pill px-3 py-2 fw-semibold">
                                        <i class="bi bi-clock-history me-1"></i> Attempted
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $attempt->digital_profile_badge_class }} rounded-pill px-3 py-2 fw-bold">
                                        <i class="bi bi-award-fill me-1"></i> {{ $attempt->digital_profile_level }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $attempt->created_at->format('M d, Y - h:i A') }}</small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-light border border-light-subtle text-center py-4 mb-0 rounded-3 text-muted">
                No certification attempts recorded yet.
            </div>
        @endif
    </div>
</div>
@endsection
