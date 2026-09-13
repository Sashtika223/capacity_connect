@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 mb-4 bg-primary text-white">
        <div class="card-body p-4 text-center">
            <h1 class="h3 font-weight-bold mb-2"><i class="fas fa-project-diagram mr-2"></i>Subject & Capacity Matchmaker</h1>
            <p class="mb-4 text-white-50">Match your learning needs with targeted courses, subject matter expert trainers, resources, and practical drills.</p>
            
            <form action="{{ route('subject-matching.index') }}" method="GET" class="row justify-content-center">
                <div class="col-md-8">
                    <div class="input-group input-group-lg shadow-sm">
                        <input type="text" name="query" class="form-control border-0" placeholder="e.g. Flood risk management, Cyclone response, Emergency logistics..." value="{{ $query }}" required>
                        <button class="btn btn-warning font-weight-bold px-4 text-dark" type="submit">
                            <i class="fas fa-search mr-1"></i> Find Matches
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(!empty($query))
        <h4 class="font-weight-bold mb-4 text-dark">
            Matching Results for "<span class="text-primary">{{ $query }}</span>"
        </h4>

        <div class="row">
            <!-- 1. Recommended Courses -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-primary"><i class="fas fa-book-open mr-2"></i>1. Recommended Courses ({{ $recommendedCourses->count() }})</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($recommendedCourses as $course)
                                <li class="list-group-item p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="font-weight-bold text-dark mb-0">{{ $course->title }}</h6>
                                        <span class="badge badge-info">{{ $course->category->name ?? 'General' }}</span>
                                    </div>
                                    <p class="small text-muted mb-2">{{ Str::limit($course->description, 90) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><i class="fas fa-user-tie mr-1"></i> Trainer: {{ $course->trainer->name ?? 'LMS Staff' }}</small>
                                        <a href="{{ route('trainee.courses.show', $course) }}" class="btn btn-xs btn-outline-primary font-weight-bold">View Course</a>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted py-4">
                                    No direct course matches found.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 2. Relevant Expert Trainers -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-success"><i class="fas fa-user-check mr-2"></i>2. Expert Trainers ({{ $recommendedTrainers->count() }})</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($recommendedTrainers as $trainer)
                                <li class="list-group-item p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-success text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; font-weight: bold;">
                                            {{ strtoupper(substr($trainer->name, 0, 2)) }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="font-weight-bold text-dark mb-0">{{ $trainer->name }}</h6>
                                            <small class="text-muted">{{ $trainer->designation ?? 'Subject Matter Expert' }} &bull; {{ $trainer->department ?? 'Operations' }}</small>
                                        </div>
                                        <span class="badge badge-success">Verified Expert</span>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted py-4">
                                    No expert trainers matched this specific topic.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 3. Learning Resources -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-light py-3">
                        <h6 class="mb-0 font-weight-bold text-warning text-dark"><i class="fas fa-file-alt mr-2"></i>3. Learning Resources ({{ $recommendedResources->count() }})</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($recommendedResources as $resource)
                                <li class="list-group-item p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="text-dark small d-block">{{ $resource->title }}</strong>
                                            <small class="text-muted"><i class="fas fa-folder mr-1"></i> {{ $resource->type }}</small>
                                        </div>
                                        @if($resource->file_path)
                                            <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank" class="btn btn-xs btn-outline-secondary"><i class="fas fa-download mr-1"></i> Open</a>
                                        @endif
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted py-4">
                                    No specific documents or media resources found.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 4. Practical Assessment Drills -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-light py-3">
                        <h6 class="mb-0 font-weight-bold text-danger"><i class="fas fa-shield-alt mr-2"></i>4. Practical Response Drills ({{ $recommendedPracticalDrills->count() }})</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($recommendedPracticalDrills as $drill)
                                <li class="list-group-item p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-dark small">{{ $drill->title }}</strong>
                                        <span class="badge badge-danger">Simulation Drill</span>
                                    </div>
                                    <p class="small text-muted mb-2">{{ Str::limit($drill->description, 80) }}</p>
                                    <form action="{{ route('trainee.practical.start', $drill) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-danger font-weight-bold"><i class="fas fa-play mr-1"></i> Launch Drill</button>
                                    </form>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted py-4">
                                    No simulation drills matched this topic.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-light p-5 text-center border shadow-xs">
            <i class="fas fa-search-location fa-3x text-muted mb-3"></i>
            <h5 class="font-weight-bold text-dark mb-1">Type any skill, domain, or disaster scenario above</h5>
            <p class="text-muted mb-0">Our matching engine will instantly connect you to active courses, subject matter expert trainers, resources, and practical simulation drills.</p>
        </div>
    @endif
</div>
@endsection
