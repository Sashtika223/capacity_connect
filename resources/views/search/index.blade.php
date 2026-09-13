@extends('layouts.app')

@section('title', 'Global Platform Search')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body p-4">
            <h2 class="fw-bold text-dark mb-3"><i class="bi bi-search text-primary me-2"></i> Global Platform Search</h2>
            
            <form action="{{ route('search.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="q" class="form-control rounded-pill" placeholder="Search courses, trainers, competencies, resources, announcements..." value="{{ $q }}">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select rounded-pill">
                        <option value="">-- All Categories --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="difficulty" class="form-select rounded-pill">
                        <option value="">-- Any Difficulty --</option>
                        <option value="beginner" {{ $difficulty == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ $difficulty == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ $difficulty == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold flex-grow-1">Search</button>
                    <a href="{{ route('search.index') }}" class="btn btn-outline-secondary rounded-pill">Reset</a>
                </div>
            </form>
        </div>
    </div>

    @if(!empty($q) || $category || $difficulty)
        <div class="row g-4">
            <!-- Courses Results -->
            <div class="col-12">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-journal-album text-primary me-2"></i>Courses ({{ $courses->count() }})</h5>
                <div class="row g-3">
                    @forelse($courses as $course)
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm border-0 rounded-4">
                                <div class="card-body">
                                    <span class="badge bg-primary mb-2">{{ $course->category->name ?? 'General' }}</span>
                                    <h6 class="fw-bold text-dark">{{ $course->title }}</h6>
                                    <p class="small text-muted mb-3">{{ Str::limit($course->description, 80) }}</p>
                                    <a href="{{ route('trainee.courses.show', $course) }}" class="btn btn-sm btn-outline-primary rounded-pill">View Course</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><p class="text-muted small">No courses found matching your criteria.</p></div>
                    @endforelse
                </div>
            </div>

            <!-- Trainers & Competencies Results -->
            <div class="col-md-6">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-badge text-success me-2"></i>Trainers ({{ $trainers->count() }})</h5>
                <ul class="list-group shadow-sm border-0 rounded-4">
                    @forelse($trainers as $tr)
                        <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="text-dark">{{ $tr->name }}</strong>
                                <div class="small text-muted">{{ $tr->designation ?? 'Instructor' }} &bull; {{ $tr->department ?? 'Operations' }}</div>
                            </div>
                            <span class="badge bg-success rounded-pill">Trainer</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small p-3">No trainers matched.</li>
                    @endforelse
                </ul>
            </div>

            <div class="col-md-6">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-award text-warning me-2"></i>Competencies ({{ $competencies->count() }})</h5>
                <ul class="list-group shadow-sm border-0 rounded-4">
                    @forelse($competencies as $comp)
                        <li class="list-group-item p-3">
                            <strong class="text-dark">{{ $comp->name }}</strong>
                            <div class="small text-muted">{{ $comp->description }}</div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small p-3">No competencies matched.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    @endif
</div>
@endsection
