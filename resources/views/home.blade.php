@extends('layouts.app')

@section('title', 'CapacityConnect - National Capacity Building & Emergency Response Portal')

@section('content')
@php
    try {
        $setting = \App\Models\HomepageSetting::first();
        $activeCoursesCount = \App\Models\Course::where('publish_status', 'published')->count();
        $totalCertificatesCount = \App\Models\Certificate::count();
        $totalUsersCount = \App\Models\User::count();
        $featuredCourses = \App\Models\Course::where('publish_status', 'published')->with('category', 'trainer')->take(3)->get();
    } catch (\Throwable $e) {
        $setting = null;
        $activeCoursesCount = 0;
        $totalCertificatesCount = 0;
        $totalUsersCount = 0;
        $featuredCourses = collect();
    }
@endphp

<!-- Important Notice Banner (If Configured) -->
@if($setting && $setting->important_notice)
    <div class="bg-warning-subtle text-warning-emphasis py-2.5 px-3 text-center fw-semibold border-bottom border-warning-subtle small">
        <i class="bi bi-megaphone-fill me-2 text-warning"></i> {{ $setting->important_notice }}
    </div>
@endif

<!-- 1. Hero Section -->
<section class="text-white py-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #090d16 0%, #0f172a 45%, #1e293b 100%); min-height: 520px;">
    <!-- Ambient glowing mesh background -->
    <div class="position-absolute top-0 start-50 translate-middle-x w-100 h-100 opacity-30 pointer-events-none" style="background: radial-gradient(circle at 20% 30%, rgba(37, 99, 235, 0.45) 0%, transparent 50%), radial-gradient(circle at 80% 70%, rgba(16, 185, 129, 0.25) 0%, transparent 50%);"></div>

    <div class="container py-lg-5 position-relative z-1">
        <div class="row justify-content-center">
            <div class="col-lg-9 text-center">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-25 text-white small fw-semibold mb-4">
                    <span class="bg-emerald rounded-circle d-inline-block" style="width: 8px; height: 8px; background-color: #10b981;"></span>
                    <i class="bi bi-shield-fill-check text-warning me-1"></i> Official Enterprise Workforce Portal
                </div>
                <h1 class="display-4 fw-extrabold text-white mb-3" style="line-height: 1.15; font-family: 'Plus Jakarta Sans', sans-serif;">
                    {{ $setting->hero_title ?? 'National Capacity Building & Disaster Response Platform' }}
                </h1>
                <p class="lead text-white-50 mb-4 fs-5 mx-auto" style="line-height: 1.6; max-width: 780px;">
                    {{ $setting->hero_description ?? 'Centralized enterprise platform for workforce capacity assessment, interactive disaster response drills, competency risk management, and verifiable professional certifications.' }}
                </p>

                <!-- Integrated Global Search Bar -->
                <form action="{{ route('search.index') }}" method="GET" class="mb-4 mx-auto" style="max-width: 680px;">
                    <div class="input-group input-group-lg shadow-lg rounded-3 overflow-hidden bg-white p-1">
                        <span class="input-group-text bg-white border-0 text-muted ps-3"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" class="form-control border-0 text-dark fs-6" placeholder="Search courses, skills, disaster drills, or internal experts..." required>
                        <button type="submit" class="btn btn-primary px-4 fw-bold rounded-2">Search Portal</button>
                    </div>
                </form>

                <div class="d-flex flex-wrap gap-3 align-items-center justify-content-center">
                    <a href="{{ route('register') }}" class="btn btn-warning btn-lg fw-bold text-dark px-4 py-3 shadow-lg rounded-3">
                        Access Portal <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('subject-matching.index') }}" class="btn btn-outline-light btn-lg px-4 py-3 rounded-3">
                        <i class="bi bi-intersect me-2"></i> Subject Matchmaker
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Featured Training Programs & Courses -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="d-flex flex-wrap align-items-end justify-content-between mb-4">
            <div>
                <span class="badge px-3 py-2 rounded-pill bg-primary-subtle text-primary border border-primary-subtle fw-semibold mb-2">
                    <i class="bi bi-journal-bookmark-fill me-1"></i> FEATURED CURRICULUM
                </span>
                <h2 class="fw-bold text-dark mb-0">High-Impact Capacity Building Courses</h2>
            </div>
            <a href="{{ route('login') }}" class="btn btn-outline-primary fw-semibold rounded-pill px-4">View All Courses <i class="bi bi-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            @forelse($featuredCourses as $course)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 card-hover overflow-hidden">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-info-subtle text-info-emphasis fw-semibold px-3 py-1 rounded-pill">{{ $course->category->name ?? 'Disaster Management' }}</span>
                                <span class="badge bg-secondary-subtle text-secondary fw-semibold text-uppercase">{{ $course->difficulty }}</span>
                            </div>
                            <h5 class="card-title fw-bold text-dark mb-2">{{ $course->title }}</h5>
                            <p class="card-text text-muted small mb-4 flex-grow-1">{{ Str::limit($course->description, 110) }}</p>
                            <div class="border-top pt-3 d-flex align-items-center justify-content-between">
                                <small class="text-muted"><i class="bi bi-clock me-1"></i> {{ $course->duration }} Hours</small>
                                <a href="{{ route('register') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">Enroll Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                        <p class="text-muted mb-0">Featured operational courses loading dynamically from database catalog.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- 3. Integrated Enterprise Platform Capabilities (Interactive Grid) -->
<section class="py-5 style-section-bg" style="background-color: #f8fafc;">
    <div class="container py-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="badge px-3 py-2 rounded-pill bg-success-subtle text-success border border-success-subtle fw-semibold mb-2">
                <i class="bi bi-cpu me-1"></i> PLATFORM CAPABILITIES
            </span>
            <h2 class="fw-extrabold text-dark mt-1">End-to-End Operational Architecture</h2>
            <p class="text-muted">A unified institutional suite for emergency readiness, competency verification, and institutional memory.</p>
        </div>

        <div class="row g-4">
            <!-- Practical Disaster Drills -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 card-hover bg-white d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-shield-exclamation fs-3"></i>
                        </div>
                        <span class="badge bg-danger-subtle text-danger fw-semibold px-2.5 py-1 rounded-pill">Simulations</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Practical Response Drills</h5>
                    <p class="text-muted small flex-grow-1 mb-4">Execute realistic branching decision trees for cyclone response, flood evacuation modeling, and emergency resource rationing.</p>
                    <a href="{{ route('subject-matching.index') }}?query=disaster" class="btn btn-outline-danger btn-sm rounded-pill fw-semibold w-100">Explore Response Drills <i class="bi bi-play-circle me-1"></i></a>
                </div>
            </div>

            <!-- Competency Risk Radar -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 card-hover bg-white d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-info-subtle text-info-emphasis rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-bar-chart-line fs-3"></i>
                        </div>
                        <span class="badge bg-info-subtle text-info-emphasis fw-semibold px-2.5 py-1 rounded-pill">Risk Intelligence</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Competency Risk Radar</h5>
                    <p class="text-muted small flex-grow-1 mb-4">Identify departmental skill shortages with transparent explanations before operational failures or crises happen.</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold w-100">View Risk Radar</a>
                </div>
            </div>

            <!-- AI Studio & Assistant -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 card-hover bg-white d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-warning bg-opacity-15 text-warning-emphasis rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-robot fs-3"></i>
                        </div>
                        <span class="badge bg-warning-subtle text-warning-emphasis fw-semibold px-2.5 py-1 rounded-pill">Governed AI</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">AI Studio & Assistant</h5>
                    <p class="text-muted small flex-grow-1 mb-4">Accelerate course content drafting with AI while ensuring human-in-the-loop review before publishing.</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-warning text-dark btn-sm rounded-pill fw-semibold w-100">Access AI Studio</a>
                </div>
            </div>

            <!-- Knowledge Graph & Expert Discovery -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 card-hover bg-white d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-diagram-3 fs-3"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success fw-semibold px-2.5 py-1 rounded-pill">Talent Discovery</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Hidden Expert Discovery</h5>
                    <p class="text-muted small flex-grow-1 mb-4">Surface top internal talent in hydro-meteorology, logistics, and emergency response via physics-based visual Knowledge Graphs.</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm rounded-pill fw-semibold w-100">Search Internal Experts</a>
                </div>
            </div>

            <!-- Offline PWA Readiness -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 card-hover bg-white d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-info bg-opacity-10 text-info-emphasis rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-wifi-off fs-3"></i>
                        </div>
                        <span class="badge bg-info-subtle text-info-emphasis fw-semibold px-2.5 py-1 rounded-pill">PWA Field Support</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Field-Ready Offline Access</h5>
                    <p class="text-muted small flex-grow-1 mb-4">Field personnel in remote or disrupted connectivity zones can access cached lessons, offline assessments, and emergency reference guides.</p>
                    <a href="{{ route('offline') }}" class="btn btn-outline-info text-dark btn-sm rounded-pill fw-semibold w-100">Test Offline Mode</a>
                </div>
            </div>

            <!-- Verifiable Certificates -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 card-hover bg-white d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-info-subtle text-info-emphasis rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-award fs-3 text-info-emphasis"></i>
                        </div>
                        <span class="badge bg-info-subtle text-info-emphasis fw-semibold px-2.5 py-1 rounded-pill">Verification</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Verifiable Credentials</h5>
                    <p class="text-muted small flex-grow-1 mb-4">Cryptographically verifiable certificates with automatic expiration tracking and public verification search.</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold w-100">Verify Certificate</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Stakeholder Operational Workflows -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5 max-w-700 mx-auto">
            <span class="badge px-3 py-2 rounded-pill bg-info-subtle text-info-emphasis border border-info-subtle fw-semibold mb-2">
                <i class="bi bi-people-fill me-1"></i> WORKFORCE ROLES
            </span>
            <h2 class="fw-extrabold text-dark">Tailored for Every Operational Role</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 border rounded-4 bg-light h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-circle me-3"><i class="bi bi-person-badge fs-4"></i></div>
                        <h5 class="fw-bold text-primary mb-0">Trainee Personnel</h5>
                    </div>
                    <p class="text-muted small mb-0">Personalized Competency Digital Twin, gamified learning badges, automated skill-gap course recommendations, and instant certificate downloads.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded-4 bg-light h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2 bg-success bg-opacity-10 text-success rounded-circle me-3"><i class="bi bi-person-workspace fs-4"></i></div>
                        <h5 class="fw-bold text-success mb-0">Instructors & Trainers</h5>
                    </div>
                    <p class="text-muted small mb-0">AI Studio content generator, practical scenario node builder, learner attention alerts, and course evaluation tools.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded-4 bg-light h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2 bg-dark bg-opacity-10 text-dark rounded-circle me-3"><i class="bi bi-building fs-4"></i></div>
                        <h5 class="fw-bold text-dark mb-0">Executive Admins</h5>
                    </div>
                    <p class="text-muted small mb-0">Enterprise analytics dashboard, capacity simulator, audit logging, and expert discovery engine.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. Executive Call to Action Banner -->
<section class="py-5 text-white text-center position-relative" style="background: linear-gradient(135deg, #0B2545 0%, #0A192F 100%);">
    <div class="container py-4 position-relative z-1">
        <h2 class="display-6 fw-extrabold mb-3 text-white">Empower Your Workforce with Verifiable Competencies</h2>
        <p class="lead text-white-50 mb-4 max-w-700 mx-auto fs-5">Join CapacityConnect LMS to assess, develop, and preserve institutional knowledge across your organization.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-warning btn-lg fw-bold text-dark px-5 py-3 shadow-lg rounded-pill">Get Started Now <i class="bi bi-arrow-right ms-2"></i></a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg fw-semibold px-4 py-3 rounded-pill">Portal Login</a>
        </div>
    </div>
</section>
@endsection
