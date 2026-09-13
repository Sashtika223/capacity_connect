@extends('layouts.trainer')

@section('title', 'Trainer Dashboard')

@section('trainer_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold"><i class="bi bi-person-workspace text-primary me-2"></i> Trainer Dashboard</h2>
        <p class="text-muted mb-0">Overview of your assigned courses, learner progress, and AI Studio drafts.</p>
    </div>
    <a href="{{ route('trainer.courses.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
        <i class="bi bi-plus-circle me-1"></i> Create New Course
    </a>
</div>

<!-- Metrics Row -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-primary text-white">
            <h6 class="text-white-50 fw-bold text-uppercase small mb-1">My Active Courses</h6>
            <div class="display-6 fw-bold">{{ $totalCourses }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-info text-white">
            <h6 class="text-white-50 fw-bold text-uppercase small mb-1">Total Enrolled Trainees</h6>
            <div class="display-6 fw-bold">{{ $totalTrainees }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-success text-white">
            <h6 class="text-white-50 fw-bold text-uppercase small mb-1">Completion Rate</h6>
            <div class="display-6 fw-bold">{{ $completionRate }}%</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-warning text-dark">
            <h6 class="text-dark opacity-75 fw-bold text-uppercase small mb-1">Avg Learner Score</h6>
            <div class="display-6 fw-bold">{{ $averageScore }}%</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Learners Needing Attention -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-danger"><i class="bi bi-exclamation-octagon me-2"></i>Learners Needing Attention</h5>
                <span class="badge bg-danger rounded-pill">{{ $learnersNeedingAttention->count() }} Trainees</span>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($learnersNeedingAttention as $stuck)
                        <li class="list-group-item p-3 border-0 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-dark">{{ $stuck->user->name ?? 'Trainee' }}</strong>
                                <span class="badge bg-danger-subtle text-danger">{{ $stuck->progress }}% Progress</span>
                            </div>
                            <small class="text-muted d-block mb-1">Course: {{ $stuck->course->title ?? 'LMS Course' }}</small>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $stuck->progress }}%;"></div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item p-4 text-center text-muted border-0">
                            <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-1"></i>
                            All enrolled trainees are progressing satisfactorily!
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- AI Studio Drafts & Pending Evaluations -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-cpu me-2"></i>AI Studio Governance & Drafts</h5>
                <a href="{{ route('trainer.ai-studio.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">Open AI Studio</a>
            </div>
            <div class="card-body p-4">
                <div class="p-3 bg-light rounded-4 mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold text-dark">Pending AI Content Reviews</div>
                        <small class="text-muted">Drafts generated awaiting human review & publication</small>
                    </div>
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill">{{ $pendingEvaluations }} Drafts</span>
                </div>
                <div class="d-grid gap-2">
                    <a href="{{ route('trainer.practical.index') }}" class="btn btn-outline-secondary rounded-pill py-2">
                        <i class="bi bi-shield-exclamation me-1"></i> Manage Practical Response Drills
                    </a>
                    <a href="{{ route('trainer.courses.index') }}" class="btn btn-primary rounded-pill py-2 fw-bold">
                        <i class="bi bi-book me-1"></i> Manage My Active Courses
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
