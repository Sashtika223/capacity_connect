@extends('layouts.app')

@section('title', 'Official System Announcements - CapacityConnect LMS')

@section('content')
<!-- Header Banner Section -->
<section class="py-5 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0d6efd 100%);">
    <div class="position-absolute top-0 start-50 translate-middle-x w-100 h-100 opacity-20 pointer-events-none" style="background: radial-gradient(circle at 70% 30%, rgba(13, 110, 253, 0.4) 0%, transparent 60%);"></div>
    <div class="container py-3 position-relative z-1">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-15 rounded-pill bg-white bg-opacity-15 border border-white border-opacity-20 text-white small fw-semibold mb-3">
                    <i class="bi bi-broadcast text-warning me-1"></i> OFFICIAL BROADCAST PORTAL
                </div>
                <h1 class="display-5 fw-extrabold text-white mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    System Announcements & Advisories
                </h1>
                <p class="lead text-white-50 mb-4 fs-5" style="line-height: 1.6;">
                    Verified operational notices, emergency response advisories, workforce training directives, and platform announcements.
                </p>

                <!-- Search Bulletins Form -->
                <form action="{{ route('announcements.index') }}" method="GET" class="max-w-600">
                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden bg-white p-1">
                        <span class="input-group-text bg-white border-0 text-muted ps-3"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control border-0 text-dark fs-6" placeholder="Search advisories, cyclone directives, or updates...">
                        <button type="submit" class="btn btn-primary px-4 fw-bold rounded-2">Search</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 text-lg-end d-none d-lg-block">
                <div class="p-4 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-15 text-start text-white shadow-lg">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 bg-warning text-dark rounded-3 fs-3">
                            <i class="bi bi-megaphone-fill"></i>
                        </div>
                        <div>
                            <div class="text-uppercase small fw-bold text-white-50">Active Advisories</div>
                            <div class="fs-2 fw-extrabold text-white">{{ $totalActive }} Bulletins</div>
                        </div>
                    </div>
                    <small class="text-white-50"><i class="bi bi-shield-check text-success me-1"></i> Published directly by Executive Portal Command.</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Announcements Content Section -->
<section class="py-5 style-section-bg" style="background-color: #f8fafc;">
    <div class="container py-2">
        <div class="row g-4">
            <!-- Left Column: Announcements Feed -->
            <div class="col-lg-8">
                @if(request('q'))
                    <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-3 rounded-4 shadow-xs border">
                        <span class="text-muted">Search results for: <strong class="text-primary">"{{ request('q') }}"</strong></span>
                        <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">Clear Search</a>
                    </div>
                @endif

                <div class="d-flex flex-column gap-4">
                    @forelse($announcements as $announcement)
                        <article class="card border-0 shadow-sm rounded-4 overflow-hidden card-hover bg-white border-start border-5 border-primary">
                            <div class="card-body p-4 p-md-5">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold">
                                            <i class="bi bi-exclamation-diamond-fill me-1"></i> Official Advisory
                                        </span>
                                        <span class="badge bg-light text-dark border rounded-pill px-3 py-1.5 fw-medium">
                                            <i class="bi bi-calendar-check text-primary me-1"></i> {{ $announcement->published_at ? $announcement->published_at->format('M d, Y') : $announcement->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-person-fill text-secondary me-1"></i> {{ $announcement->creator->name ?? 'System Administrator' }}
                                    </small>
                                </div>

                                <h3 class="fw-bold text-dark mb-3 h4" style="line-height: 1.35;">
                                    {{ $announcement->title }}
                                </h3>

                                <div class="text-secondary mb-4 fs-6" style="line-height: 1.7; whitespace: pre-line;">
                                    {!! nl2br(e($announcement->content)) !!}
                                </div>

                                <div class="border-top pt-3 d-flex align-items-center justify-content-between flex-wrap gap-2 text-muted small">
                                    <div class="d-flex align-items-center gap-3">
                                        <span><i class="bi bi-clock me-1"></i> Published {{ $announcement->published_at ? $announcement->published_at->diffForHumans() : $announcement->created_at->diffForHumans() }}</span>
                                        @if($announcement->expires_at)
                                            <span class="text-warning-emphasis"><i class="bi bi-hourglass-split me-1"></i> Valid until {{ $announcement->expires_at->format('M d, Y') }}</span>
                                        @endif
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1"><i class="bi bi-shield-lock me-1"></i> Verified Operational Directive</span>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                            <div class="py-4">
                                <i class="bi bi-broadcast display-3 text-secondary opacity-40 d-block mb-3"></i>
                                <h4 class="fw-bold text-dark">No Active Announcements Found</h4>
                                <p class="text-muted max-w-500 mx-auto">There are currently no active public announcements matching your query. Check back later for official capacity building advisories.</p>
                                @if(request('q'))
                                    <a href="{{ route('announcements.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">View All Bulletins</a>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($announcements->hasPages())
                    <div class="mt-4">
                        {{ $announcements->links() }}
                    </div>
                @endif
            </div>

            <!-- Right Sidebar Widgets -->
            <div class="col-lg-4">
                <!-- Emergency Operational Contacts Widget -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-telephone-fill text-danger me-2"></i> Operational Support</h5>
                    <p class="text-muted small mb-3">For urgent operational assistance regarding disaster preparedness courses or certification issues:</p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li class="mb-2.5 d-flex align-items-center"><i class="bi bi-envelope me-2 text-primary"></i> <strong>Email:</strong> admin.capacity.connect.lms@gmail.com</li>
                        <li class="mb-2.5 d-flex align-items-center"><i class="bi bi-clock me-2 text-primary"></i> <strong>Support Hours:</strong> 24/7 Monitoring</li>
                        <li class="d-flex align-items-center"><i class="bi bi-shield-check me-2 text-success"></i> <strong>Portal Status:</strong> Fully Operational</li>
                    </ul>
                </div>

                <!-- Quick Matchmaker CTA -->
                <div class="card border-0 shadow-sm rounded-4 p-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                    <h5 class="fw-bold mb-2"><i class="bi bi-intersect text-info me-2"></i> Subject Matchmaker</h5>
                    <p class="text-white-50 small mb-4">Find expert trainers and emergency response courses mapped to hydro-meteorological competencies.</p>
                    <a href="{{ route('subject-matching.index') }}" class="btn btn-warning rounded-pill px-4 fw-bold w-100 text-dark">
                        Launch Matchmaker <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
