@extends('layouts.app')

@section('title', 'Admin Portal')

@section('content')
<style>
    .admin-sidebar-nav .nav-link {
        padding: 0.65rem 1rem !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        border-radius: 10px !important;
        transition: all 0.2s ease !important;
        color: #334155 !important;
        display: flex !important;
        align-items: center !important;
        text-decoration: none !important;
    }
    .admin-sidebar-nav .nav-link i {
        font-size: 1.1rem !important;
        transition: color 0.2s ease !important;
    }
    .admin-sidebar-nav .nav-link:hover {
        background-color: #F1F5F9 !important;
        color: #0F172A !important;
        transform: translateX(3px) !important;
    }
    .admin-sidebar-nav .nav-link.active {
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%) !important;
        color: #FFFFFF !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2) !important;
    }
    .admin-sidebar-nav .nav-link.active i {
        color: #F59E0B !important;
    }
    .sidebar-section-header {
        font-size: 0.7rem !important;
        letter-spacing: 0.08em !important;
        font-weight: 700 !important;
        color: #94A3B8 !important;
        text-transform: uppercase !important;
        padding: 0.75rem 0.75rem 0.25rem 0.75rem !important;
    }
</style>

<div class="container-fluid py-4 px-lg-4">
    <div class="row g-4">
        <!-- Expanded Admin Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block">
            <div class="bg-white sidebar shadow-sm rounded-4 p-3 border border-slate-200">
                <div class="d-flex align-items-center gap-2 px-2 pb-3 mb-3 border-bottom">
                    <div class="bg-dark text-warning p-2 rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-speedometer2 fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif;">Admin Control</h6>
                        <small class="text-muted" style="font-size: 0.7rem;">System Operations</small>
                    </div>
                </div>

                <div class="admin-sidebar-nav">
                    <!-- CORE -->
                    <div class="sidebar-section-header">Core Management</div>
                    <ul class="nav flex-column gap-1 mb-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2.5"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people me-2.5"></i> User Directory
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.trainer-requests*') ? 'active' : '' }}" href="{{ route('admin.trainer-requests.index') }}">
                                <i class="bi bi-person-check me-2.5"></i> Trainer Approvals
                            </a>
                        </li>
                    </ul>

                    <!-- LEARNING -->
                    <div class="sidebar-section-header">Learning Engine</div>
                    <ul class="nav flex-column gap-1 mb-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.courses*') ? 'active' : '' }}" href="{{ route('admin.courses.index') }}">
                                <i class="bi bi-journal-text me-2.5"></i> Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                                <i class="bi bi-tags me-2.5"></i> Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.assessments*') ? 'active' : '' }}" href="{{ route('admin.assessments') }}">
                                <i class="bi bi-file-earmark-check me-2.5"></i> Assessments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.certificate-management*') ? 'active' : '' }}" href="{{ route('admin.certificate-management.index') }}">
                                <i class="bi bi-patch-check me-2.5"></i> Certificates
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.trainer-certifications*') ? 'active' : '' }}" href="{{ route('admin.trainer-certifications.index') }}">
                                <i class="bi bi-award me-2.5"></i> Trainer Certs
                            </a>
                        </li>
                    </ul>

                    <!-- INSIGHTS -->
                    <div class="sidebar-section-header">Analytics & Impact</div>
                    <ul class="nav flex-column gap-1 mb-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.training-impact*') ? 'active' : '' }}" href="{{ route('admin.training-impact.index') }}">
                                <i class="bi bi-graph-up-arrow me-2.5"></i> Training Impact
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.feedback*') ? 'active' : '' }}" href="{{ route('admin.feedback') }}">
                                <i class="bi bi-chat-square-text me-2.5"></i> Feedback Center
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.capacity-simulator*') ? 'active' : '' }}" href="{{ route('admin.capacity-simulator.index') }}">
                                <i class="bi bi-cpu me-2.5"></i> Capacity Simulator
                            </a>
                        </li>
                    </ul>

                    <!-- ADVANCED -->
                    <div class="sidebar-section-header">Intelligence & Risk</div>
                    <ul class="nav flex-column gap-1 mb-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.competencies.index') ? 'active' : '' }}" href="{{ route('admin.competencies.index') }}">
                                <i class="bi bi-bullseye me-2.5"></i> Competencies
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.competency-mapping*') ? 'active' : '' }}" href="{{ route('admin.competency-mapping.index') }}">
                                <i class="bi bi-diagram-3 me-2.5"></i> Competency Mapping
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.risk-radar*') ? 'active' : '' }}" href="{{ route('admin.risk-radar.index') }}">
                                <i class="bi bi-exclamation-triangle me-2.5"></i> Risk Radar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.expert-discovery*') ? 'active' : '' }}" href="{{ route('admin.expert-discovery.index') }}">
                                <i class="bi bi-person-search me-2.5"></i> Expert Discovery
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.knowledge-graph*') ? 'active' : '' }}" href="{{ route('admin.knowledge-graph.index') }}">
                                <i class="bi bi-bounding-box-circles me-2.5"></i> Knowledge Graph
                            </a>
                        </li>
                    </ul>

                    <!-- SYSTEM -->
                    <div class="sidebar-section-header">System Operations</div>
                    <ul class="nav flex-column gap-1">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}" href="{{ route('admin.announcements.index') }}">
                                <i class="bi bi-megaphone me-2.5"></i> Announcements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.homepage-settings*') ? 'active' : '' }}" href="{{ route('admin.homepage-settings.index') }}">
                                <i class="bi bi-gear me-2.5"></i> Homepage Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.audit-logs*') ? 'active' : '' }}" href="{{ route('admin.audit-logs.index') }}">
                                <i class="bi bi-shield-lock me-2.5"></i> Audit Logs
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <main class="col-md-9 col-lg-10">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 text-white bg-danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('admin_content')
        </main>
    </div>
</div>
@endsection