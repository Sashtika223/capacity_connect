@extends('layouts.trainee')

@section('title', 'Trainee Feedback')

@section('trainee_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">My Feedback</h2>
</div>

<div class="row g-4">
    <!-- Submit Feedback Form -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Submit New Feedback</h5>
            </div>
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success rounded-3">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
                @endif

                @if($eligibleCourses->count() > 0)
                    <form action="{{ route('trainee.feedback.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Course</label>
                            <select name="course_id" class="form-select rounded-3" required>
                                <option value="">Choose a course...</option>
                                @foreach($eligibleCourses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Course Rating</label>
                            <select name="course_rating" class="form-select rounded-3" required>
                                <option value="5" selected>⭐⭐⭐⭐⭐ (5/5 - Excellent)</option>
                                <option value="4">⭐⭐⭐⭐ (4/5 - Good)</option>
                                <option value="3">⭐⭐⭐ (3/5 - Average)</option>
                                <option value="2">⭐⭐ (2/5 - Below Average)</option>
                                <option value="1">⭐ (1/5 - Poor)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Trainer Rating (Dr. Rajesh Sharma)</label>
                            <select name="trainer_rating" class="form-select rounded-3" required>
                                <option value="5" selected>⭐⭐⭐⭐⭐ (5/5 - Excellent)</option>
                                <option value="4">⭐⭐⭐⭐ (4/5 - Good)</option>
                                <option value="3">⭐⭐⭐ (3/5 - Average)</option>
                                <option value="2">⭐⭐ (2/5 - Below Average)</option>
                                <option value="1">⭐ (1/5 - Poor)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Comments (Optional)</label>
                            <textarea name="comments" class="form-control rounded-3" rows="3" placeholder="What did you like about this course?"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Suggestions (Optional)</label>
                            <textarea name="suggestions" class="form-control rounded-3" rows="3" placeholder="How can we improve?"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm">Submit Feedback</button>
                    </form>
                @else
                    <div class="text-center py-5">
                        @if($feedbacks->count() > 0)
                            <i class="bi bi-check-circle text-success fs-1 mb-3"></i>
                            <h6 class="fw-bold">You're all caught up!</h6>
                            <p class="text-muted small">You have submitted feedback for all available courses.</p>
                        @else
                            <i class="bi bi-journal-x text-muted fs-1 mb-3"></i>
                            <h6 class="fw-bold text-dark">No Courses Available</h6>
                            <p class="text-muted small">There are currently no active courses to review.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Submitted Feedback History -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Submission History</h5>
            </div>
            <div class="card-body p-4">
                @if($feedbacks->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($feedbacks as $fb)
                            <div class="list-group-item px-0 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold mb-0">{{ $fb->course->title }}</h6>
                                    @if($fb->status === 'approved')
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3">Approved</span>
                                    @elseif($fb->status === 'rejected')
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-3">Rejected</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning rounded-pill px-3">Pending Review</span>
                                    @endif
                                </div>
                                <div class="d-flex gap-3 text-muted small mb-2">
                                    <span><i class="bi bi-star-fill text-warning"></i> Course: {{ $fb->course_rating }}/5</span>
                                    <span><i class="bi bi-person-fill text-primary"></i> Trainer: {{ $fb->trainer_rating }}/5</span>
                                </div>
                                @if($fb->comments)
                                    <p class="small text-dark mb-1"><strong>Comments:</strong> {{ Str::limit($fb->comments, 100) }}</p>
                                @endif
                                <small class="text-muted">Submitted {{ $fb->created_at->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-chat-square-text text-muted fs-1 mb-3"></i>
                        <p class="text-muted">You haven't submitted any feedback yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
