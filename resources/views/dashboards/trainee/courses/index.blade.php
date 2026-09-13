@extends('layouts.trainee')

@section('trainee_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Available Courses</h2>
</div>

<div class="row g-4">
    @forelse($courses as $course)
    @php
        $ytId = '4oMjovaNB_s'; // Cyclone Related Lectures & Disaster Management
        $isEnrolled = in_array($course->id, $enrolledCourseIds ?? []);
    @endphp
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative hover-shadow transition">
            <!-- YouTube Video Thumbnail Header -->
            <div class="position-relative bg-dark" style="height: 180px; overflow: hidden;">
                <img src="https://img.youtube.com/vi/{{ $ytId }}/hqdefault.jpg" alt="{{ $course->title }}" class="w-100 h-100 object-fit-cover opacity-75">
                <a href="{{ route('trainee.courses.show', $course->id) }}" class="position-absolute top-50 start-50 translate-middle text-white text-decoration-none">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 54px; height: 54px; transition: transform 0.2s ease;">
                        <i class="bi {{ $isEnrolled ? 'bi-play-fill' : 'bi-lock-fill' }} fs-2"></i>
                    </div>
                </a>
                <span class="position-absolute bottom-0 start-0 m-2 badge bg-dark bg-opacity-75 text-white">
                    <i class="bi bi-cc-square me-1 text-warning"></i> English Captions
                </span>
                <span class="position-absolute top-0 end-0 m-2 badge {{ $course->difficulty == 'beginner' ? 'bg-success' : ($course->difficulty == 'intermediate' ? 'bg-warning text-dark' : 'bg-danger') }}">
                    {{ ucfirst($course->difficulty) }}
                </span>
            </div>

            <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="badge bg-light text-dark border">{{ $course->category->name ?? 'Uncategorized' }}</span>
                    @if($isEnrolled)
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-bold small">
                            <i class="bi bi-check-circle-fill me-1"></i> Enrolled
                        </span>
                    @else
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2.5 py-1 fw-bold small">
                            <i class="bi bi-lock-fill me-1"></i> Not Enrolled
                        </span>
                    @endif
                </div>
                <h5 class="fw-bold text-dark mt-1 mb-2">
                    <a href="{{ route('trainee.courses.show', $course->id) }}" class="text-decoration-none text-dark hover-primary">
                        {{ $course->title }}
                    </a>
                </h5>
                <p class="text-muted small mb-2"><i class="bi bi-person me-1"></i> {{ $course->trainer->name ?? 'Dr. Rajesh Sharma' }}</p>
                <p class="text-muted small mb-4 flex-grow-1">{{ Str::limit($course->description, 90) }}</p>
                
                <div class="mt-auto">
                    @if($isEnrolled)
                        <a href="{{ route('trainee.courses.show', $course->id) }}" class="btn btn-danger w-100 rounded-pill fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center">
                            <i class="bi bi-play-circle-fill fs-5 me-2"></i> Watch Video & Learn
                        </a>
                    @else
                        <div class="d-flex flex-column gap-2">
                            <form action="{{ route('trainee.courses.enroll', $course->id) }}" method="POST" class="w-100 m-0">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-plus-fill fs-5 me-2"></i> Enroll Course
                                </button>
                            </form>
                            <a href="{{ route('trainee.courses.show', $course->id) }}" class="btn btn-outline-secondary btn-sm w-100 rounded-pill fw-semibold py-1.5 text-center">
                                <i class="bi bi-info-circle me-1"></i> View Course Details
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <h5 class="text-muted">No active courses available right now.</h5>
    </div>
    @endforelse
</div>
@endsection
