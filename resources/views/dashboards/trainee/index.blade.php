@extends('layouts.trainee')

@section('title', 'Trainee Dashboard')

@section('trainee_content')
<!-- Welcome Banner -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary text-white overflow-hidden">
    <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-2 fw-bold"><i class="bi bi-fire me-1"></i> {{ $user->learning_streak ?? 1 }} Day Learning Streak!</span>
            <h2 class="fw-bold mb-1">Welcome back, {{ $user->name }}!</h2>
            <p class="text-white-50 mb-0">Department: {{ $user->department ?? 'Operations' }} &bull; Designation: {{ $user->designation ?? 'Capacity Specialist' }}</p>
        </div>
        <div class="mt-3 mt-md-0 text-md-end">
            <div class="bg-white text-primary p-3 rounded-4 shadow-sm d-inline-block text-center">
                <div class="small text-uppercase fw-bold text-muted">Gamification Points</div>
                <div class="display-6 fw-bold text-primary">{{ $user->points ?? 0 }} <span class="h6 text-muted">pts</span></div>
                <span class="badge bg-success rounded-pill px-3">Level {{ $user->level ?? 1 }} Trainee</span>
            </div>
        </div>
    </div>
</div>

<!-- Metrics Row -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center p-3">
            <h6 class="text-muted fw-bold text-uppercase small">Enrolled Courses</h6>
            <div class="display-6 fw-bold text-primary">{{ $enrolledCount }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center p-3">
            <h6 class="text-muted fw-bold text-uppercase small">Completed Courses</h6>
            <div class="display-6 fw-bold text-success">{{ $completedCount }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center p-3">
            <h6 class="text-muted fw-bold text-uppercase small">Certificates Earned</h6>
            <div class="display-6 fw-bold text-warning-emphasis">{{ $certificatesCount }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center p-3">
            <h6 class="text-muted fw-bold text-uppercase small">Average Score</h6>
            <div class="display-6 fw-bold text-info">{{ $averageScore }}%</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Current Courses in Progress -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-journal-bookmark text-primary me-2"></i>Current Courses in Progress</h5>
                <a href="{{ route('trainee.courses') }}" class="btn btn-sm btn-outline-primary rounded-pill">View All Courses</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($currentEnrollments as $enrollment)
                        <div class="list-group-item p-3 border-0 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold text-dark mb-0">{{ $enrollment->course->title }}</h6>
                                <span class="badge bg-primary rounded-pill">{{ $enrollment->progress }}% Complete</span>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $enrollment->progress }}%;"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="bi bi-person me-1"></i> Trainer: {{ $enrollment->course->trainer->name ?? 'LMS Staff' }}</small>
                                <a href="{{ route('trainee.courses.show', $enrollment->course) }}" class="btn btn-sm btn-primary rounded-pill px-3">Continue Learning</a>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-book-half display-4 d-block mb-2 text-secondary"></i>
                            <p class="mb-0">You have no active courses in progress. Explore recommended courses below!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recommended Courses based on Skill Gaps -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-stars text-warning me-2"></i>Recommended Courses for Your Skill Profile</h5>
            </div>
            <div class="card-body p-3">
                <div class="row g-3">
                    @foreach($recommendedCourses as $course)
                        <div class="col-md-6">
                            <div class="card h-100 border rounded-4 p-3 shadow-xs">
                                <span class="badge bg-info-subtle text-info-emphasis w-auto align-self-start mb-2">Recommended</span>
                                <h6 class="fw-bold text-dark mb-1">{{ $course->title }}</h6>
                                <p class="small text-muted mb-3">{{ Str::limit($course->description, 80) }}</p>
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><i class="bi bi-clock me-1"></i> {{ $course->duration ?? 2 }} hrs</small>
                                    <a href="{{ route('trainee.courses.show', $course) }}" class="btn btn-sm btn-outline-primary rounded-pill">View Details</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar Widgets -->
    <div class="col-lg-4">
        <!-- Gamification Badges Widget -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-award text-warning me-2"></i>Earned Badges ({{ $badges->count() }})</h5>
            </div>
            <div class="card-body p-3">
                <div class="d-flex flex-wrap gap-2">
                    @forelse($badges as $badge)
                        <div class="badge bg-warning-subtle text-dark border border-warning p-2 rounded-3 d-flex align-items-center">
                            <i class="bi {{ $badge->icon ?? 'bi-award' }} text-warning me-2 fs-5"></i>
                            <div>
                                <div class="fw-bold small">{{ $badge->name }}</div>
                                <div class="text-muted" style="font-size: 0.65rem;">{{ $badge->description }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Complete assessments and practical drills to unlock learning badges!</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Skill Gap Summary -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-bar-chart-line text-danger me-2"></i>My Skill Gaps</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($skillGaps as $gap)
                        <li class="list-group-item p-3 border-0 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="small text-dark">{{ $gap['competency']->name }}</strong>
                                <span class="badge bg-danger-subtle text-danger">Gap: -{{ $gap['gap'] }} level</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($gap['current'] / max(1, $gap['required'])) * 100 }}%;"></div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item p-3 text-center text-muted small border-0">
                            No active skill gaps identified. Outstanding performance!
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Upcoming Assessments -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-file-earmark-check text-info me-2"></i>Upcoming Assessments</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($upcomingAssessments as $asm)
                        <li class="list-group-item p-3 border-0 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="small text-dark">{{ $asm->title }}</strong>
                                <span class="badge bg-info-subtle text-info-emphasis">{{ $asm->duration }} mins</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">{{ $asm->course->title ?? 'Course Assessment' }}</small>
                                <a href="{{ route('trainee.assessments.show', $asm) }}" class="btn btn-xs btn-primary rounded-pill px-3">Start</a>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item p-3 text-center text-muted small border-0">
                            No pending assessments.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
