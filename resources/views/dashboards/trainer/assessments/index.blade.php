@extends('layouts.trainer')

@section('trainer_content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Course Assessments</h2>
        <p class="text-muted small mb-0">Manage tests, generate MCQs with Gemini AI, and set passing thresholds.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-warning fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#generateAiModal">
            <i class="bi bi-stars me-1"></i> Generate with Gemini AI
        </button>
        <a href="{{ route('trainer.assessments.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-1"></i> Create Manual Assessment
        </a>
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

<div class="row">
    @forelse($assessments as $assessment)
    <div class="col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="badge {{ $assessment->status == 'published' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ ucfirst($assessment->status) }}
                    </span>
                    <span class="badge bg-primary">{{ $assessment->course->course_code ?? 'Course' }}</span>
                </div>
                <h5 class="fw-bold text-primary mt-2">{{ $assessment->title }}</h5>
                <p class="text-muted small mb-2"><i class="bi bi-book me-1"></i> {{ $assessment->subject ?? 'General Assessment' }}</p>
                <div class="d-flex gap-3 text-muted small mt-3">
                    <span><i class="bi bi-clock me-1"></i> {{ $assessment->duration }} mins</span>
                    <span><i class="bi bi-question-circle me-1"></i> {{ $assessment->questions->count() }} Questions</span>
                    <span><i class="bi bi-check-circle me-1"></i> Pass: {{ $assessment->passing_score }}%</span>
                </div>
                <div class="mt-4">
                    <a href="{{ route('trainer.assessments.edit', $assessment->id) }}" class="btn btn-outline-primary w-100 rounded-3">Manage Questions & Answers</a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <div class="py-4">
            <i class="bi bi-journal-x display-4 text-muted mb-3 d-block"></i>
            <h5 class="text-muted fw-bold">No course assessments created yet.</h5>
            <p class="small text-muted mb-3">Click **Generate with Gemini AI** to auto-create assessment questions from your course materials.</p>
            <button type="button" class="btn btn-warning fw-bold rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#generateAiModal">
                <i class="bi bi-stars me-1"></i> Generate with Gemini AI
            </button>
        </div>
    </div>
    @endforelse
</div>

<!-- Gemini AI Assessment Generator Modal -->
<div class="modal fade" id="generateAiModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-3 bg-warning bg-opacity-20 text-warning">
                        <i class="bi bi-stars fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark">Generate Assessment with Gemini AI</h5>
                        <small class="text-muted">Auto-create structured MCQs from course content or document files</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('trainer.assessments.generate-ai') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Target Course <span class="text-danger">*</span></label>
                            <select name="course_id" class="form-select rounded-3" required>
                                <option value="">Select a Course</option>
                                @php
                                    $trainerCourses = \App\Models\Course::where('trainer_id', auth()->id())->get();
                                    if ($trainerCourses->isEmpty()) {
                                        $trainerCourses = \App\Models\Course::where('publish_status', 'published')->get();
                                    }
                                @endphp
                                @foreach($trainerCourses as $c)
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
                            <label class="form-label fw-bold">Option A: Upload Reference Document (PDF, TXT, DOCX)</label>
                            <input type="file" name="document" class="form-control rounded-3" accept=".pdf,.txt,.docx,.doc">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Option B: Or Paste Custom Reference Material</label>
                            <textarea name="pasted_content" class="form-control rounded-3" rows="4" placeholder="Paste manual excerpts, protocol guidelines, or operational notes..."></textarea>
                            <small class="text-muted">If both options are blank, Gemini AI will extract text from the course's published lessons.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark rounded-pill px-4 shadow-sm">
                        <i class="bi bi-stars me-1"></i> Generate & Publish MCQs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
