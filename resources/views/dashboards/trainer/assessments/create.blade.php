@extends('layouts.trainer')

@section('trainer_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Create Assessment</h2>
    <a href="{{ route('trainer.assessments.index') }}" class="btn btn-outline-secondary">Back to Assessments</a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-md-5">
        <form method="POST" action="{{ route('trainer.assessments.store') }}">
            @csrf
            
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Course</label>
                    <select class="form-select" name="course_id" required>
                        <option value="">Select a Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Assessment Title</label>
                    <input type="text" class="form-control" name="title" required placeholder="e.g. Mid-term Exam">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Subject / Topic</label>
                    <input type="text" class="form-control" name="subject" placeholder="e.g. Database Systems">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Duration (minutes)</label>
                    <input type="number" class="form-control" name="duration" value="60" required min="1">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Passing Score (%)</label>
                    <input type="number" class="form-control" name="passing_score" value="50" required min="1" max="100">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Start Date (Optional)</label>
                    <input type="datetime-local" class="form-control" name="start_date">
                </div>
                <div class="col-md-6">
                    <label class="form-label">End Date / Deadline (Optional)</label>
                    <input type="datetime-local" class="form-control" name="end_date">
                </div>

                <div class="col-12">
                    <label class="form-label">Instructions for Trainees</label>
                    <textarea class="form-control" name="instructions" rows="4"></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">Save Assessment</button>
            </div>
        </form>
    </div>
</div>
@endsection
