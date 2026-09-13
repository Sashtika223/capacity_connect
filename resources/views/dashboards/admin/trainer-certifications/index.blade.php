@extends('layouts.admin')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Trainer Certification & Eligibility Management</h2>
        <p class="text-muted mb-0">Monitor trainer profile completion status, assign course scenario assessments, and oversee certification lifecycles.</p>
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

<!-- Summary Metrics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
            <h6 class="text-muted fw-bold mb-2">Total Trainers</h6>
            <h3 class="fw-bold text-dark mb-0">{{ $trainers->count() }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
            <h6 class="text-muted fw-bold mb-2">Eligible (100% Profile)</h6>
            <h3 class="fw-bold text-success mb-0">
                {{ $trainers->filter(fn($t) => optional($t->trainerProfile)->isComplete())->count() }}
            </h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
            <h6 class="text-muted fw-bold mb-2">Incomplete Profiles</h6>
            <h3 class="fw-bold text-warning mb-0">
                {{ $trainers->filter(fn($t) => !optional($t->trainerProfile)->isComplete())->count() }}
            </h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
            <h6 class="text-muted fw-bold mb-2">Available Courses</h6>
            <h3 class="fw-bold text-primary mb-0">{{ $courses->count() }}</h3>
        </div>
    </div>
</div>

<!-- Trainers Eligibility List -->
<div class="card border-0 shadow-sm rounded-4 mb-5">
    <div class="card-body p-4">
        <h5 class="fw-bold text-primary mb-4">
            <i class="bi bi-people-fill me-2"></i> Trainer Eligibility & Certification Status
        </h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Trainer Name</th>
                        <th>Department</th>
                        <th>Profile Progress</th>
                        <th>Eligibility Status</th>
                        <th>Active Certifications</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trainers as $trainer)
                        @php
                            $profile = $trainer->trainerProfile;
                            $completion = $profile ? $profile->completion_percentage : 0;
                            $isEligible = $profile ? $profile->isComplete() : false;
                            $certsCount = $trainer->trainerCertifications->count();
                            $certifiedCount = $trainer->trainerCertifications->whereIn('status', ['certified', 'renewal_passed'])->count();
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($trainer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $trainer->name }}</h6>
                                        <small class="text-muted">{{ $trainer->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-dark">{{ $profile->department ?? 'N/A' }}</span>
                            </td>
                            <td style="width: 180px;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar {{ $completion == 100 ? 'bg-success' : 'bg-warning' }}" role="progressbar" style="width: {{ $completion }}%;"></div>
                                    </div>
                                    <small class="fw-bold text-dark">{{ $completion }}%</small>
                                </div>
                            </td>
                            <td>
                                @if($isEligible)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                        <i class="bi bi-shield-check me-1"></i> Eligible
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">
                                        <i class="bi bi-exclamation-triangle me-1"></i> Incomplete Profile
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3">
                                    {{ $certifiedCount }} Active / {{ $certsCount }} Total
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.trainer-certifications.show', $trainer->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 me-1">
                                    <i class="bi bi-eye-fill me-1"></i> Details
                                </a>

                                @if($isEligible)
                                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#assignModal_{{ $trainer->id }}">
                                        <i class="bi bi-plus-circle me-1"></i> Assign Cert
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" disabled title="Trainer profile must be 100% complete">
                                        <i class="bi bi-lock-fill me-1"></i> Assign Locked
                                    </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal for Assigning Course Assessment -->
                        @if($isEligible)
                            <div class="modal fade" id="assignModal_{{ $trainer->id }}" tabindex="-1" aria-hidden="true">
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
                                                <p class="text-muted small">Assign a course scenario assessment to <strong>{{ $trainer->name }}</strong>. Upon passing with 80%+, the trainer becomes certified to teach this course.</p>

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
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No trainers found in system.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
