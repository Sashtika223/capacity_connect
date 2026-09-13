@extends('layouts.trainee')

@section('trainee_content')
<div class="mb-4">
    <a href="{{ route('trainee.courses') }}" class="text-decoration-none">&larr; Back to Course Catalog</a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex gap-2 mb-3">
                    <span class="badge bg-light text-dark border">{{ $course->category->name ?? 'Uncategorized' }}</span>
                    <span class="badge bg-light text-dark border"><i class="bi bi-clock"></i> {{ $course->duration ?? 'Self-paced' }}</span>
                </div>
                
                <h2 class="fw-bold text-primary mb-3">{{ $course->title }}</h2>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="text-muted">By <strong>{{ $course->trainer->name ?? 'Dr. Rajesh Sharma' }}</strong></div>
                </div>

                <h5 class="fw-bold mt-4">Description</h5>
                <p class="text-muted">{{ $course->description ?? 'No description available for this course.' }}</p>

                @if($course->learning_objectives)
                <h5 class="fw-bold mt-4">Learning Objectives</h5>
                <p class="text-muted">{{ $course->learning_objectives }}</p>
                @endif
                
                @if($course->prerequisites)
                <h5 class="fw-bold mt-4">Prerequisites</h5>
                <p class="text-muted">{{ $course->prerequisites }}</p>
                @endif
            </div>
        </div>

        <!-- Featured Video Player with English Captions & Interactive Transcripts -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-dark text-white p-3 p-md-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center">
                    <i class="bi bi-youtube text-danger fs-3 me-2"></i>
                    <div>
                        <h5 class="fw-bold mb-0 text-white">Course Video Lecture</h5>
                        <small class="text-white-50">Auto-Enabling English Subtitles (CC)</small>
                    </div>
                </div>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold">
                    <i class="bi bi-cc-square-fill me-1"></i> English Captions & Transcript Available
                </span>
            </div>
            
            <div class="ratio ratio-16x9 bg-black">
                @php
                    $ytId = '4oMjovaNB_s'; // Cyclone Related Lectures & Disaster Management
                @endphp
                <iframe 
                    id="courseVideoPlayer"
                    src="https://www.youtube.com/embed/{{ $ytId }}?cc_load_policy=1&cc_lang_pref=en&hl=en&enablejsapi=1&rel=0" 
                    title="{{ $course->title }} Video Lesson" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    allowfullscreen 
                    class="w-100 h-100 border-0">
                </iframe>
            </div>

            <!-- YouTube Direct Link & Captions Notice -->
            <div class="bg-dark p-2 px-3 text-white-50 small d-flex justify-content-between align-items-center">
                <span><i class="bi bi-info-circle me-1 text-warning"></i> English captions (CC) are enabled. If embed is blocked by your browser ad-blocker:</span>
                <a href="https://www.youtube.com/watch?v={{ $ytId }}" target="_blank" rel="noopener noreferrer" class="btn btn-xs btn-outline-light rounded-pill px-3 py-1">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Open on YouTube
                </a>
            </div>

            <!-- English Transcript Section -->
            <div class="card-body p-4 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-file-text-fill text-primary me-2"></i> Interactive English Video Transcript
                    </h5>
                    <span class="text-muted small"><i class="bi bi-hand-index-thumb me-1"></i> Click timestamp to jump to video section</span>
                </div>

                <div class="bg-white p-3 rounded-4 border shadow-sm style-transcript" style="max-height: 240px; overflow-y: auto;">
                    <div class="d-flex flex-column gap-3">
                        <div class="p-2 rounded-3 bg-light-subtle hover-shadow border-start border-primary border-3 cursor-pointer" onclick="jumpToTime(0)" style="cursor: pointer;">
                            <div class="d-flex align-items-center mb-1">
                                <span class="badge bg-primary text-white rounded-pill px-2 py-1 me-2 font-monospace">00:00</span>
                                <strong class="text-dark">Introduction & Scenario Overview</strong>
                            </div>
                            <p class="mb-0 text-muted small ps-4">Welcome to the {{ $course->title }} training session. In this video, we review standard operating protocols for emergency response and capacity building.</p>
                        </div>

                        <div class="p-2 rounded-3 bg-light-subtle hover-shadow border-start border-primary border-3 cursor-pointer" onclick="jumpToTime(45)" style="cursor: pointer;">
                            <div class="d-flex align-items-center mb-1">
                                <span class="badge bg-primary text-white rounded-pill px-2 py-1 me-2 font-monospace">00:45</span>
                                <strong class="text-dark">Tactical Command & Satellite Communications</strong>
                            </div>
                            <p class="mb-0 text-muted small ps-4">When terrestrial communication networks fail, secondary satellite transmission channels must be deployed immediately to synchronize operational field units.</p>
                        </div>

                        <div class="p-2 rounded-3 bg-light-subtle hover-shadow border-start border-primary border-3 cursor-pointer" onclick="jumpToTime(90)" style="cursor: pointer;">
                            <div class="d-flex align-items-center mb-1">
                                <span class="badge bg-primary text-white rounded-pill px-2 py-1 me-2 font-monospace">01:30</span>
                                <strong class="text-dark">Resource Allocation & GIS Mapping</strong>
                            </div>
                            <p class="mb-0 text-muted small ps-4">Resource distribution requires real-time GIS mapping and field compliance checks before certifying operational readiness during emergency deployment.</p>
                        </div>

                        <div class="p-2 rounded-3 bg-light-subtle hover-shadow border-start border-primary border-3 cursor-pointer" onclick="jumpToTime(135)" style="cursor: pointer;">
                            <div class="d-flex align-items-center mb-1">
                                <span class="badge bg-primary text-white rounded-pill px-2 py-1 me-2 font-monospace">02:15</span>
                                <strong class="text-dark">Post-Incident Review & Sign-Off Criteria</strong>
                            </div>
                            <p class="mb-0 text-muted small ps-4">All practical drills must be documented with 100% profile verification and scenario attempt logs prior to official credential sign-off.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function jumpToTime(seconds) {
            const iframe = document.getElementById('courseVideoPlayer');
            if (iframe && iframe.contentWindow) {
                iframe.contentWindow.postMessage(JSON.stringify({
                    'event': 'command',
                    'func': 'seekTo',
                    'args': [seconds, true]
                }), '*');
                iframe.contentWindow.postMessage(JSON.stringify({
                    'event': 'command',
                    'func': 'playVideo',
                    'args': []
                }), '*');
            }
        }
        </script>

        <h4 class="fw-bold mt-5 mb-3">Course Curriculum</h4>
        @forelse($course->modules as $module)
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary mb-3">{{ $module->title }}</h5>
                    @if($module->description)
                        <p class="text-muted small mb-3">{{ $module->description }}</p>
                    @endif
                    
                    <ul class="list-group list-group-flush">
                        @forelse($module->lessons as $lesson)
                            <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-play-circle-fill text-primary fs-5 me-3"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $lesson->title }}</h6>
                                        <small class="text-muted">{{ $lesson->duration }} min</small>
                                    </div>
                                </div>
                                @if($isEnrolled)
                                    <a href="{{ route('trainee.courses.lesson', [$course->id, $lesson->id]) }}" class="btn btn-sm btn-outline-primary">Start Lesson</a>
                                @else
                                    <span class="badge bg-light text-muted border"><i class="bi bi-lock-fill"></i> Locked</span>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item px-0 text-muted">No lessons available in this module yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        @empty
            <div class="alert alert-info border-0 rounded-4 shadow-sm">This course doesn't have any modules published yet.</div>
        @endforelse
    </div>

    </div>
    <!-- Feedback Section -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 p-md-5">
            <h4 class="fw-bold mb-4">Student Feedback</h4>
            
            @if($course->feedback && $course->feedback->count() > 0)
                <div class="row g-4">
                    @foreach($course->feedback as $fb)
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-4 h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0">{{ $fb->user->name }}</h6>
                                    <div class="text-warning small">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="bi {{ $i <= $fb->course_rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted mb-2 d-block">{{ $fb->created_at->diffForHumans() }}</small>
                                @if($fb->comments)
                                    <p class="mb-0 text-dark small">"{{ $fb->comments }}"</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-light border rounded-3 text-center py-4">
                    <i class="bi bi-chat-square-text text-muted fs-3 mb-2 d-block"></i>
                    No feedback available for this course yet.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Enrollment Sidebar -->
<div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
            <div class="card-body p-4 text-center">
                <h4 class="fw-bold mb-4">Enrollment</h4>
                
                @if($isEnrolled)
                    <div class="alert alert-success mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i> You are enrolled in this course.
                    </div>
                    
                    @php
                        $firstLesson = $course->modules->first()?->lessons->first();
                    @endphp
                    
                    @if($firstLesson)
                        <a href="{{ route('trainee.courses.lesson', [$course->id, $firstLesson->id]) }}" class="btn btn-primary btn-lg w-100">Continue Learning</a>
                    @else
                        <button class="btn btn-secondary btn-lg w-100" disabled>No Content Available</button>
                    @endif
                    
                @else
                    <form method="POST" action="{{ route('trainee.courses.enroll', $course->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">Enroll for Free</button>
                    </form>
                    <p class="text-muted small">Start learning immediately after enrolling.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
