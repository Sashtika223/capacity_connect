@extends('layouts.trainer')

@section('trainer_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">My Courses</h2>
    <a href="{{ route('trainer.courses.create') }}" class="btn btn-primary">Create New Course</a>
</div>

<div class="row">
    @forelse($courses as $course)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="badge {{ $course->publish_status == 'published' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ ucfirst($course->publish_status) }}
                    </span>
                    <span class="text-muted small">{{ $course->course_code }}</span>
                </div>
                <h5 class="fw-bold text-primary">{{ $course->title }}</h5>
                <p class="text-muted small mt-2">{{ Str::limit($course->description, 100) }}</p>
                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('trainer.courses.edit', $course->id) }}" class="btn btn-outline-primary flex-grow-1">Manage Course</a>
                    <a href="{{ route('trainer.courses.enrollees', $course->id) }}" class="btn btn-outline-secondary" title="View Enrollees">
                        <i class="bi bi-people"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <h5 class="text-muted">You haven't created any courses yet.</h5>
    </div>
    @endforelse
</div>
@endsection
