@extends('layouts.app')

@section('title', 'About Us - CapacityConnect Enterprise LMS')

@section('content')
<!-- Hero Header Section -->
<section class="text-white py-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #090d16 0%, #0f172a 45%, #1e293b 100%); min-height: 480px;">
    <div class="position-absolute top-0 start-50 translate-middle-x w-100 h-100 opacity-30 pointer-events-none" style="background: radial-gradient(circle at 20% 30%, rgba(37, 99, 235, 0.45) 0%, transparent 50%), radial-gradient(circle at 80% 70%, rgba(245, 158, 11, 0.25) 0%, transparent 50%);"></div>

    <div class="container py-lg-5 position-relative z-1">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-25 text-white small fw-semibold mb-4">
                    <i class="bi bi-shield-fill-check text-warning"></i> National Workforce Readiness Portal
                </div>
                <h1 class="display-4 fw-extrabold text-white mb-3" style="line-height: 1.15; font-family: 'Plus Jakarta Sans', sans-serif;">
                    Building Resilient Workforces for Emergency Readiness & Enterprise Mastery
                </h1>
                <p class="lead text-white-50 mb-4 fs-5 mx-auto" style="line-height: 1.6; max-width: 820px;">
                    CapacityConnect is the premier workforce capacity building platform—unifying operational training, practical disaster response simulations, AI-assisted curriculum drafting, and verifiable competency credentials into one seamless ecosystem.
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center align-items-center">
                    <a href="{{ route('subject-matching.index') }}" class="btn btn-warning btn-lg fw-bold text-dark px-4 py-3 shadow-lg rounded-3">
                        <i class="bi bi-intersect me-2"></i> Subject Matchmaker
                    </a>
                    <a href="{{ route('contact.index') }}" class="btn btn-outline-light btn-lg px-4 py-3 rounded-3">
                        <i class="bi bi-envelope me-2"></i> Contact Headquarters
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Impact Metrics Bar -->
<section class="py-4 border-bottom shadow-sm bg-white">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-6 col-md-3">
                <div class="p-3">
                    <h2 class="display-6 fw-extrabold text-primary mb-1">15,000+</h2>
                    <p class="text-muted small fw-semibold text-uppercase mb-0">Active Personnel Trained</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3">
                    <h2 class="display-6 fw-extrabold text-warning mb-1">450+</h2>
                    <p class="text-muted small fw-semibold text-uppercase mb-0">Disaster Drills Executed</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3">
                    <h2 class="display-6 fw-extrabold text-success mb-1">32,000+</h2>
                    <p class="text-muted small fw-semibold text-uppercase mb-0">Verifiable Certifications</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3">
                    <h2 class="display-6 fw-extrabold text-info mb-1">99.9%</h2>
                    <p class="text-muted small fw-semibold text-uppercase mb-0">Field Operational Readiness</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Core Values Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="badge px-3 py-2 rounded-pill bg-primary-subtle text-primary border border-primary-subtle fw-semibold mb-2">
                <i class="bi bi-compass me-1"></i> INSTITUTIONAL FOUNDATION
            </span>
            <h2 class="fw-extrabold text-dark mt-1">Our Mission & Strategic Vision</h2>
            <p class="text-muted">Designed to eliminate skill blindspots, empower personnel under critical scenarios, and preserve institutional knowledge across agencies.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 card-hover bg-white">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                        <i class="bi bi-bullseye fs-3"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Our Mission</h4>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        To deliver continuous workforce capability mapping, instant emergency preparedness training, and validated skill certificates that empower organizations to respond decisively to complex crises.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 card-hover bg-white">
                    <div class="bg-warning bg-opacity-15 text-warning-emphasis rounded-3 d-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                        <i class="bi bi-eye fs-3"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Our Strategic Vision</h4>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        To set the global benchmark for institutional capacity building—where every responder’s skills are quantified, certified, and instantly discoverable during crisis operations.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 card-hover bg-white">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                        <i class="bi bi-shield-check fs-3"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Governed Excellence</h4>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        Combining AI-assisted curriculum drafting with strict human-in-the-loop validation, complete audit logs, and cryptographic verification for institutional trust.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Platform Architecture Pillars -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="badge px-3 py-2 rounded-pill bg-info-subtle text-info-emphasis border border-info-subtle fw-semibold mb-2">
                <i class="bi bi-cpu me-1"></i> ENTERPRISE CAPABILITIES
            </span>
            <h2 class="fw-extrabold text-dark mt-1">Core Architecture & Capabilities</h2>
            <p class="text-muted">A deep dive into the technology powering CapacityConnect LMS across all operational domains.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="p-4 rounded-4 border bg-light h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2.5 bg-danger bg-opacity-10 text-danger rounded-3 me-3"><i class="bi bi-diagram-3-fill fs-4"></i></div>
                        <h5 class="fw-bold text-dark mb-0">Practical Response Drills</h5>
                    </div>
                    <p class="text-muted small mb-0">Interactive branching decision trees designed for emergency situations like Category 4 cyclones, rapid flood evacuations, and resource rationing.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="p-4 rounded-4 border bg-light h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2.5 bg-primary bg-opacity-10 text-primary rounded-3 me-3"><i class="bi bi-bar-chart-steps fs-4"></i></div>
                        <h5 class="fw-bold text-dark mb-0">Competency Risk Radar</h5>
                    </div>
                    <p class="text-muted small mb-0">Real-time risk scoring matrix that detects organizational skill gaps before they result in operational bottlenecks.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="p-4 rounded-4 border bg-light h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2.5 bg-success bg-opacity-10 text-success rounded-3 me-3"><i class="bi bi-search-heart fs-4"></i></div>
                        <h5 class="fw-bold text-dark mb-0">Hidden Expert Discovery</h5>
                    </div>
                    <p class="text-muted small mb-0">Physics-based visual Knowledge Graphs to immediately locate internal subject-matter experts in high-demand domains.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="p-4 rounded-4 border bg-light h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2.5 bg-warning bg-opacity-15 text-warning-emphasis rounded-3 me-3"><i class="bi bi-robot fs-4"></i></div>
                        <h5 class="fw-bold text-dark mb-0">Governed AI Studio</h5>
                    </div>
                    <p class="text-muted small mb-0">Accelerates lesson creation and scenario assessment design while holding every output to trainer approval and audit history.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="p-4 rounded-4 border bg-light h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2.5 bg-info bg-opacity-10 text-info-emphasis rounded-3 me-3"><i class="bi bi-patch-check-fill fs-4"></i></div>
                        <h5 class="fw-bold text-dark mb-0">Verifiable Credentials</h5>
                    </div>
                    <p class="text-muted small mb-0">Unique QR-coded digital certificates with public verification URL, recertification workflows, and expiration safeguards.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="p-4 rounded-4 border bg-light h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2.5 bg-secondary bg-opacity-10 text-secondary rounded-3 me-3"><i class="bi bi-wifi-off fs-4"></i></div>
                        <h5 class="fw-bold text-dark mb-0">Offline Field PWA Sync</h5>
                    </div>
                    <p class="text-muted small mb-0">Progressive Web App engine that caches core learning modules, offline quizzes, and field guides when network connectivity drops.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-5 text-white text-center position-relative" style="background: linear-gradient(135deg, #0B2545 0%, #0A192F 100%);">
    <div class="container py-4 position-relative z-1">
        <h2 class="display-6 fw-extrabold mb-3 text-white">Ready to Empower Your Enterprise Workforce?</h2>
        <p class="lead text-white-50 mb-4 max-w-700 mx-auto fs-5">Get started today with CapacityConnect to assess readiness, close skill gaps, and verify operational competencies.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-warning btn-lg fw-bold text-dark px-5 py-3 shadow-lg rounded-pill">Create Account <i class="bi bi-arrow-right ms-2"></i></a>
            <a href="{{ route('contact.index') }}" class="btn btn-outline-light btn-lg fw-semibold px-4 py-3 rounded-pill">Talk to Headquarters</a>
        </div>
    </div>
</section>
@endsection
