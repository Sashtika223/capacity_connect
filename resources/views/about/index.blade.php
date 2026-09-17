@extends('layouts.app')

@section('title', 'About Us - CapacityConnect Enterprise LMS')

@section('content')
<!-- Distinct Hero Header Section for About Us -->
<section class="text-white py-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0b1329 0%, #111c38 50%, #1e293b 100%); min-height: 420px;">
    <div class="position-absolute top-0 start-50 translate-middle-x w-100 h-100 opacity-25 pointer-events-none" style="background: radial-gradient(circle at 30% 20%, rgba(59, 130, 246, 0.4) 0%, transparent 60%), radial-gradient(circle at 70% 80%, rgba(245, 158, 11, 0.25) 0%, transparent 60%);"></div>

    <div class="container py-lg-4 position-relative z-1">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <!-- Dedicated About Page Badge -->
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-20 text-white small fw-bold mb-3">
                    <i class="bi bi-info-circle-fill text-warning"></i> ABOUT CAPACITYCONNECT LMS
                </div>
                <h1 class="display-4 fw-extrabold text-white mb-3" style="line-height: 1.15; font-family: 'Plus Jakarta Sans', sans-serif;">
                    Empowering Organizations & Responders with Next-Gen Capacity Building
                </h1>
                <p class="lead text-white-50 mb-4 fs-5 mx-auto" style="line-height: 1.6; max-width: 820px;">
                    CapacityConnect is an enterprise-grade Learning Management System built specifically to quantify workforce competencies, simulate real-time crisis scenarios, and issue tamper-proof credentials.
                </p>
                <div class="d-flex flex-wrap gap-2 justify-content-center align-items-center">
                    <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill small"><i class="bi bi-check-circle-fill me-1"></i> Disaster Preparedness</span>
                    <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill small"><i class="bi bi-cpu-fill me-1"></i> AI-Assisted Curriculum</span>
                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill small"><i class="bi bi-shield-lock-fill me-1"></i> Verifiable Credentials</span>
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

<!-- Who We Are & Story Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="pe-lg-3">
                    <span class="badge px-3 py-2 rounded-pill bg-primary-subtle text-primary border border-primary-subtle fw-semibold mb-3">
                        <i class="bi bi-building me-1"></i> OUR PURPOSE & ORIGIN
                    </span>
                    <h2 class="fw-extrabold text-dark display-6 mb-3">Transforming Institutional Skill Mapping & Crisis Action</h2>
                    <p class="text-muted mb-3 fs-6" style="line-height: 1.7;">
                        In times of crisis—whether natural disasters, infrastructure challenges, or rapid enterprise transformations—traditional learning management systems fall short. They track completion rates rather than field readiness.
                    </p>
                    <p class="text-muted mb-4 fs-6" style="line-height: 1.7;">
                        <strong>CapacityConnect</strong> was designed from the ground up to bridge the gap between classroom theory and real-world deployment. By integrating interactive disaster simulation branching engines, real-time risk radar analytics, and AI-governed course creation, we ensure organizations are always mission-ready.
                    </p>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light border">
                                <i class="bi bi-diagram-3-fill fs-2 text-primary"></i>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Branching Drills</h6>
                                    <small class="text-muted">Simulate critical decisions</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light border">
                                <i class="bi bi-graph-up-arrow fs-2 text-success"></i>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Risk Analytics</h6>
                                    <small class="text-muted">Locate skill blindspots</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="p-4 p-md-5 rounded-4 text-white shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);">
                    <div class="position-absolute top-0 end-0 p-4 opacity-10">
                        <i class="bi bi-shield-check display-1"></i>
                    </div>
                    <h3 class="fw-bold text-warning mb-3">Built for High-Stakes Environments</h3>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-3 fs-6 text-white-50">
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-warning mt-1"></i>
                            <div><strong class="text-white">Emergency Response Agencies:</strong> Train first responders in realistic scenarios with instantaneous feedback.</div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-warning mt-1"></i>
                            <div><strong class="text-white">Enterprise Organizations:</strong> Upskill workforces at scale with verifiable competency mapping.</div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-warning mt-1"></i>
                            <div><strong class="text-white">Certified Trainers:</strong> Utilize AI Studio tools to draft modules while keeping full human oversight.</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Core Values Section -->
<section class="py-5 bg-light border-top border-bottom">
    <div class="container py-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="badge px-3 py-2 rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle fw-semibold mb-2">
                <i class="bi bi-compass me-1"></i> GUIDING PRINCIPLES
            </span>
            <h2 class="fw-extrabold text-dark mt-1">Mission, Vision & Strategic Values</h2>
            <p class="text-muted">The core pillars driving the architecture and execution of CapacityConnect LMS.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
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
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
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
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
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

<!-- Key Platform Capabilities -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="badge px-3 py-2 rounded-pill bg-info-subtle text-info-emphasis border border-info-subtle fw-semibold mb-2">
                <i class="bi bi-cpu me-1"></i> ARCHITECTURE & MODULES
            </span>
            <h2 class="fw-extrabold text-dark mt-1">Platform Core Capabilities</h2>
            <p class="text-muted">Built with advanced features to manage competencies across all operational domains.</p>
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
        <h2 class="display-6 fw-extrabold mb-3 text-white">Explore CapacityConnect Platform</h2>
        <p class="lead text-white-50 mb-4 max-w-700 mx-auto fs-5">Join thousands of personnel enhancing operational readiness and earning verifiable credentials.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('subject-matching.index') }}" class="btn btn-warning btn-lg fw-bold text-dark px-4 py-3 shadow-lg rounded-pill">
                <i class="bi bi-intersect me-2"></i> Subject Matchmaker
            </a>
            <a href="{{ route('contact.index') }}" class="btn btn-outline-light btn-lg fw-semibold px-4 py-3 rounded-pill">
                <i class="bi bi-envelope me-2"></i> Contact Headquarters
            </a>
        </div>
    </div>
</section>
@endsection
