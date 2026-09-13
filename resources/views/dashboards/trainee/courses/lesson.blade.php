@extends('layouts.app')

@section('title', $lesson->title)

@section('content')
<div class="container-fluid bg-light py-3 border-bottom">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('trainee.courses.show', $course->id) }}" class="text-decoration-none text-secondary me-3">&larr; Back to Course</a>
            <span class="fw-bold text-dark">{{ $course->title }}</span>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0">Course Content</h5>
                </div>
                <div class="card-body p-0 mt-3">
                    <div class="accordion accordion-flush" id="courseAccordion">
                        @foreach($course->modules as $index => $module)
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $module->id == $lesson->course_module_id ? '' : 'collapsed' }} bg-light fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#module{{ $module->id }}">
                                        {{ $module->title }}
                                    </button>
                                </h2>
                                <div id="module{{ $module->id }}" class="accordion-collapse collapse {{ $module->id == $lesson->course_module_id ? 'show' : '' }}" data-bs-parent="#courseAccordion">
                                    <div class="accordion-body p-0">
                                        <ul class="list-group list-group-flush">
                                            @foreach($module->lessons as $modLesson)
                                                <a href="{{ route('trainee.courses.lesson', [$course->id, $modLesson->id]) }}" class="text-decoration-none">
                                                    <li class="list-group-item px-4 py-3 {{ $modLesson->id == $lesson->id ? 'bg-primary text-white border-primary' : 'text-dark' }}">
                                                        <div class="d-flex align-items-center">
                                                            @php
                                                                $isCompletedList = \App\Models\LessonProgress::where('user_id', auth()->id())->where('lesson_id', $modLesson->id)->exists();
                                                            @endphp
                                                            @if($isCompletedList)
                                                                <i class="bi bi-check-circle-fill text-success me-3"></i>
                                                            @else
                                                                <i class="bi bi-play-circle {{ $modLesson->id == $lesson->id ? 'text-white' : 'text-muted' }} me-3"></i>
                                                            @endif
                                                            <span class="small">{{ $modLesson->title }}</span>
                                                        </div>
                                                    </li>
                                                </a>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-lg-9 col-md-8">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <h2 class="fw-bold mb-4">{{ $lesson->title }}</h2>
                    
                    @php
                        $ytId = '4oMjovaNB_s'; // Cyclone Related Lectures & Disaster Management
                    @endphp

                    <!-- Embedded YouTube Video Player with Subtitles Enabled -->
                    <div class="ratio ratio-16x9 bg-dark rounded-4 overflow-hidden mb-2 shadow-sm">
                        <iframe 
                            id="lessonVideoPlayer"
                            src="https://www.youtube.com/embed/{{ $ytId }}?cc_load_policy=1&cc_lang_pref=en&hl=en&enablejsapi=1&rel=0" 
                            title="{{ $lesson->title }} Video Lesson" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen 
                            class="w-100 h-100 border-0">
                        </iframe>
                    </div>
                    
                    <!-- Direct Link Fallback -->
                    <div class="d-flex justify-content-between align-items-center mb-4 px-2 small text-muted">
                        <span><i class="bi bi-cc-square text-success me-1"></i> English Subtitles Enabled</span>
                        <a href="https://www.youtube.com/watch?v={{ $ytId }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Open on YouTube
                        </a>
                    </div>

                    <!-- English Transcript Section -->
                    <div class="p-4 bg-light rounded-4 mb-4 border">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0">
                                <i class="bi bi-file-text-fill text-primary me-2"></i> Interactive English Lesson Transcript
                            </h5>
                            <span class="text-muted small"><i class="bi bi-hand-index-thumb me-1"></i> Click timestamp to jump</span>
                        </div>

                        <div class="bg-white p-3 rounded-4 border shadow-sm style-transcript" style="max-height: 240px; overflow-y: auto;">
                            <div class="d-flex flex-column gap-3">
                                <div class="p-2 rounded-3 bg-light-subtle hover-shadow border-start border-primary border-3 cursor-pointer" onclick="jumpToLessonTime(0)" style="cursor: pointer;">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-primary text-white rounded-pill px-2 py-1 me-2 font-monospace">00:00</span>
                                        <strong class="text-dark">Lesson Introduction & Objectives</strong>
                                    </div>
                                    <p class="mb-0 text-muted small ps-4">Welcome to {{ $lesson->title }}. In this module, we will explore practical guidelines and field execution steps.</p>
                                </div>

                                <div class="p-2 rounded-3 bg-light-subtle hover-shadow border-start border-primary border-3 cursor-pointer" onclick="jumpToLessonTime(45)" style="cursor: pointer;">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-primary text-white rounded-pill px-2 py-1 me-2 font-monospace">00:45</span>
                                        <strong class="text-dark">Core Concepts & Operational Protocol</strong>
                                    </div>
                                    <p class="mb-0 text-muted small ps-4">Reviewing critical response protocols, communication safety checks, and step-by-step field coordination.</p>
                                </div>

                                <div class="p-2 rounded-3 bg-light-subtle hover-shadow border-start border-primary border-3 cursor-pointer" onclick="jumpToLessonTime(90)" style="cursor: pointer;">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-primary text-white rounded-pill px-2 py-1 me-2 font-monospace">01:30</span>
                                        <strong class="text-dark">Field Demonstration & Case Study</strong>
                                    </div>
                                    <p class="mb-0 text-muted small ps-4">Real-world scenario walkthrough demonstrating resource deployment and incident management best practices.</p>
                                </div>

                                <div class="p-2 rounded-3 bg-light-subtle hover-shadow border-start border-primary border-3 cursor-pointer" onclick="jumpToLessonTime(135)" style="cursor: pointer;">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-primary text-white rounded-pill px-2 py-1 me-2 font-monospace">02:15</span>
                                        <strong class="text-dark">Key Takeaways & Assessment Prep</strong>
                                    </div>
                                    <p class="mb-0 text-muted small ps-4">Summary of key points required for completing the module assessment and securing your certification credentials.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                    function jumpToLessonTime(seconds) {
                        const iframe = document.getElementById('lessonVideoPlayer');
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

                    <div class="content-wrapper fs-5 text-muted lh-lg">
                        {{ $lesson->content ?? 'No text content available for this lesson.' }}
                    </div>
                    
                    <hr class="my-5">
                    
                    <div class="d-flex justify-content-between align-items-center bg-light p-4 rounded-4">
                        <div>
                            @if($isCompleted)
                                <span class="badge bg-success p-2 fs-6"><i class="bi bi-check-circle-fill me-2"></i> Completed</span>
                            @else
                                <span class="badge bg-secondary p-2 fs-6">Pending Completion</span>
                            @endif
                        </div>
                        
                        @if(!$isCompleted)
                            <form method="POST" action="{{ route('trainee.courses.lesson.complete', [$course->id, $lesson->id]) }}">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg px-4">
                                    <i class="bi bi-check2-all me-2"></i> Mark as Complete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
