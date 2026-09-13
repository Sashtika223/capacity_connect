@extends('layouts.admin')

@section('title', 'Admin - Assessment Statistics & Gemini AI Generator')

@section('admin_content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Assessment Management & Statistics</h2>
        <p class="text-muted small mb-0">Platform-wide test metrics, pass rates, and Gemini AI assessment generation.</p>
    </div>
    <div>
        <button type="button" class="btn btn-warning fw-bold text-dark rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#adminAiModal">
            <i class="bi bi-stars me-1"></i> Generate Assessment with Gemini AI
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 text-white bg-danger">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 text-center">
                <h6 class="text-muted mb-2 text-uppercase fw-bold small">Total Assessments</h6>
                <h2 class="fw-bold text-primary mb-0">{{ $totalAssessments }}</h2>
                <small class="text-muted">{{ $publishedAssessments }} published</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 text-center">
                <h6 class="text-muted mb-2 text-uppercase fw-bold small">Total Attempts</h6>
                <h2 class="fw-bold text-primary mb-0">{{ $totalAttempts }}</h2>
                <small class="text-muted">{{ $completedAttempts }} completed</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 text-center">
                <h6 class="text-muted mb-2 text-uppercase fw-bold small">Average Score</h6>
                <h2 class="fw-bold text-primary mb-0">{{ round($averageScore) }}%</h2>
                <small class="text-muted">Across all completed</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 text-center">
                <h6 class="text-muted mb-2 text-uppercase fw-bold small">Platform Pass Rate</h6>
                <h2 class="fw-bold text-success mb-0">{{ $passRate }}%</h2>
                <small class="text-muted">Percentage of passed attempts</small>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-warning bg-opacity-20 text-warning rounded-4 fs-3">
                    <i class="bi bi-stars"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1">Gemini AI Automated Test Generator</h5>
                    <p class="text-muted small mb-0">Admins can select any published course and upload protocol manuals or paste operational notes to auto-generate MCQ assessment tests.</p>
                </div>
            </div>
            <button type="button" class="btn btn-warning fw-bold text-dark rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#adminAiModal">
                <i class="bi bi-plus-lg me-1"></i> Launch AI Generator
            </button>
        </div>
    </div>
</div>

<!-- Gemini AI Assessment Generator Modal for Admin -->
<div class="modal fade" id="adminAiModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-3 bg-warning bg-opacity-20 text-warning">
                        <i class="bi bi-stars fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark">Gemini AI Assessment Test Generator</h5>
                        <small class="text-muted">System Administrator Control</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.assessments.generate-ai') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Select Target Course <span class="text-danger">*</span></label>
                            <select name="course_id" class="form-select rounded-3" required>
                                <option value="">Select a Course</option>
                                @foreach(\App\Models\Course::where('publish_status', 'published')->get() as $c)
                                    <option value="{{ $c->id }}">{{ $c->title }} ({{ $c->course_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Difficulty Level</label>
                            <select name="difficulty" class="form-select rounded-3">
                                <option value="easy">Easy (Foundational)</option>
                                <option value="medium" selected>Medium (Standard)</option>
                                <option value="hard">Hard (Advanced Operational)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Number of Questions</label>
                            <select name="num_questions" class="form-select rounded-3">
                                <option value="5" selected>5 Questions</option>
                                <option value="10">10 Questions</option>
                                <option value="15">15 Questions</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Upload Reference Document (PDF, TXT, DOCX)</label>
                            <input type="file" name="document" class="form-control rounded-3" accept=".pdf,.txt,.docx,.doc">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Or Paste Reference Material / Operating Standard</label>
                            <textarea name="pasted_content" class="form-control rounded-3" rows="4" placeholder="Paste guidelines, emergency manuals, or operational procedures..."></textarea>
                            <small class="text-muted">If both inputs are blank, Gemini AI will auto-extract material from the selected course's published lessons.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark rounded-pill px-4 shadow-sm">
                        <i class="bi bi-stars me-1"></i> Auto-Generate Assessment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
