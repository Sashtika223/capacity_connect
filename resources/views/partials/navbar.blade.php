<style>
    /* Executive Header Scope & Resets */
    .cc-top-bar {
        background: linear-gradient(90deg, #090D16 0%, #0F172A 100%) !important;
        color: #CBD5E1 !important;
        font-size: 0.825rem !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        padding: 0.5rem 0 !important;
    }
    .cc-top-bar a, .cc-navbar a {
        text-decoration: none !important;
    }
    .cc-social-btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 30px !important;
        height: 30px !important;
        border-radius: 50% !important;
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #CBD5E1 !important;
        font-size: 0.85rem !important;
        transition: all 0.2s ease !important;
        text-decoration: none !important;
    }
    .cc-social-btn:hover {
        background-color: #F59E0B !important;
        color: #090D16 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.35) !important;
    }
    .cc-navbar {
        background-color: #FFFFFF !important;
        border-bottom: 1px solid #E2E8F0 !important;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.07) !important;
        padding: 0.75rem 0 !important;
    }
    .cc-brand-badge {
        width: 44px !important;
        height: 44px !important;
        border-radius: 12px !important;
        background: linear-gradient(135deg, #0F172A 0%, #1E3A8A 100%) !important;
        color: #F59E0B !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 1.35rem !important;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.22) !important;
        border: 1.5px solid rgba(245, 158, 11, 0.3) !important;
        flex-shrink: 0 !important;
    }
    .cc-nav-link {
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.45rem !important;
        padding: 0.6rem 1.15rem !important;
        font-family: 'Inter', -apple-system, sans-serif !important;
        font-weight: 600 !important;
        font-size: 0.925rem !important;
        color: #334155 !important;
        border-radius: 10px !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        text-decoration: none !important;
        white-space: nowrap !important;
        border: 1px solid transparent !important;
        background: transparent !important;
    }
    .cc-nav-link i {
        font-size: 1.05rem !important;
        color: #64748B !important;
        transition: color 0.2s ease !important;
    }
    .cc-nav-link:hover {
        color: #0F172A !important;
        background-color: #F1F5F9 !important;
        border-color: #E2E8F0 !important;
        text-decoration: none !important;
        transform: translateY(-1px) !important;
    }
    .cc-nav-link:hover i {
        color: #1E40AF !important;
    }
    .cc-nav-link.active {
        color: #1E40AF !important;
        background-color: #EFF6FF !important;
        border-color: #BFDBFE !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        box-shadow: inset 0 0 0 1px rgba(30, 64, 175, 0.1) !important;
    }
    .cc-nav-link.active i {
        color: #1E40AF !important;
    }
    .cc-search-btn {
        width: 40px !important;
        height: 40px !important;
        border-radius: 50% !important;
        background-color: #F8FAFC !important;
        border: 1px solid #E2E8F0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #334155 !important;
        transition: all 0.2s ease !important;
        text-decoration: none !important;
    }
    .cc-search-btn:hover {
        background-color: #0F172A !important;
        color: #FFFFFF !important;
        border-color: #0F172A !important;
        transform: scale(1.05) !important;
    }
    .cc-btn-navy {
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%) !important;
        border: 1px solid #0F172A !important;
        color: #FFFFFF !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        padding: 0.55rem 1.35rem !important;
        border-radius: 10px !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15) !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        white-space: nowrap !important;
    }
    .cc-btn-navy:hover, .cc-btn-navy:focus {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%) !important;
        color: #FFFFFF !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.25) !important;
        text-decoration: none !important;
    }
    .cc-btn-gold {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%) !important;
        border: none !important;
        color: #090D16 !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
        padding: 0.55rem 1.35rem !important;
        border-radius: 10px !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        box-shadow: 0 4px 14px rgba(217, 119, 6, 0.28) !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        white-space: nowrap !important;
    }
    .cc-btn-gold:hover, .cc-btn-gold:focus {
        background: linear-gradient(135deg, #FBBF24 0%, #F59E0B 100%) !important;
        color: #090D16 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(217, 119, 6, 0.38) !important;
        text-decoration: none !important;
    }
    .cc-btn-outline-navy {
        background-color: transparent !important;
        border: 2px solid #0F172A !important;
        color: #0F172A !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        padding: 0.5rem 1.25rem !important;
        border-radius: 10px !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        white-space: nowrap !important;
    }
    .cc-btn-outline-navy:hover, .cc-btn-outline-navy:focus {
        background-color: #0F172A !important;
        color: #FFFFFF !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.18) !important;
        text-decoration: none !important;
    }
</style>

<!-- Top Information Bar -->
<div class="cc-top-bar d-none d-lg-block">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-4">
                <span><i class="bi bi-envelope-fill me-1.5" style="color: #F59E0B;"></i> info@capacityconnect.gov</span>
                <span><i class="bi bi-telephone-fill me-1.5" style="color: #F59E0B;"></i> +1 (800) 555-0199</span>
                <span><i class="bi bi-geo-alt-fill me-1.5" style="color: #F59E0B;"></i> National Capacity HQ &bull; Emergency Ops</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge rounded-pill px-3 py-1 me-2 small fw-bold" style="background: rgba(16, 185, 129, 0.18); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.35); font-size: 0.725rem; letter-spacing: 0.06em;">
                    <i class="bi bi-circle-fill me-1" style="font-size: 0.55rem; vertical-align: middle;"></i> SYSTEM ACTIVE
                </span>
                <button type="button" class="btn btn-sm text-warning border border-warning border-opacity-40 rounded-pill px-2.5 py-0.5 me-3 small fw-bold" onclick="startPortalTour()" style="font-size: 0.725rem; background: rgba(245, 158, 11, 0.1);">
                    <i class="bi bi-compass-fill me-1"></i> Take Tour
                </button>
                <span class="me-2 text-uppercase fw-bold small" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #94A3B8;">Connect:</span>
                <a href="#" class="cc-social-btn" title="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="cc-social-btn" title="Twitter / X"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="cc-social-btn" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                <a href="#" class="cc-social-btn" title="YouTube"><i class="bi bi-youtube"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Main Navigation Header Bar -->
<nav class="navbar navbar-expand-lg cc-navbar sticky-top">
    <div class="container">
        <a class="d-inline-flex align-items-center gap-3 text-decoration-none" href="{{ url('/') }}" style="text-decoration: none !important;">
            <div class="cc-brand-badge">
                <i class="bi bi-shield-check"></i>
            </div>
            <div class="d-flex flex-column">
                <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.3rem; letter-spacing: -0.03em; color: #0F172A; line-height: 1.1; display: block;">
                    CAPACITY <span style="color: #D97706;">CONNECT</span>
                </span>
                <span style="font-size: 0.65rem; letter-spacing: 0.1em; font-weight: 700; color: #64748B; text-transform: uppercase; display: block; line-height: 1; margin-top: 2px;">
                    Workforce LMS Portal
                </span>
            </div>
        </a>

        <button class="navbar-toggler border-0 shadow-none p-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-center gap-1">
                <li class="nav-item">
                    <a class="cc-nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="cc-nav-link" href="{{ url('/#about') }}">
                        <i class="bi bi-info-circle"></i> About Us
                    </a>
                </li>
                @php
                    $coursesUrl = route('trainee.courses');
                    if (auth()->check()) {
                        if (auth()->user()->isAdmin()) {
                            $coursesUrl = route('admin.courses.index');
                        } elseif (auth()->user()->isTrainer()) {
                            $coursesUrl = route('trainer.courses.index');
                        }
                    }
                @endphp
                <li class="nav-item">
                    <a class="cc-nav-link {{ request()->is('*courses*') ? 'active' : '' }}" href="{{ $coursesUrl }}">
                        <i class="bi bi-journal-bookmark"></i> Courses
                    </a>
                </li>
                <li class="nav-item">
                    <a class="cc-nav-link {{ request()->routeIs('subject-matching*') ? 'active' : '' }}" href="{{ route('subject-matching.index') }}">
                        <i class="bi bi-intersect"></i> Matchmaker
                    </a>
                </li>
                <li class="nav-item">
                    <a class="cc-nav-link" href="{{ url('/#capabilities') }}">
                        <i class="bi bi-cpu"></i> Capabilities
                    </a>
                </li>
                <li class="nav-item">
                    <a class="cc-nav-link {{ request()->routeIs('contact*') ? 'active' : '' }}" href="{{ route('contact.index') }}">
                        <i class="bi bi-envelope"></i> Contact Us
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                <a href="{{ route('search.index') }}" class="cc-search-btn" title="Global Search">
                    <i class="bi bi-search fs-6"></i>
                </a>

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="cc-btn-navy">
                            <i class="bi bi-speedometer2"></i> Admin Dashboard
                        </a>
                    @elseif(auth()->user()->isTrainer())
                        <a href="{{ route('trainer.dashboard') }}" class="cc-btn-navy">
                            <i class="bi bi-easel"></i> Trainer Dashboard
                        </a>
                    @else
                        <a href="{{ route('trainee.dashboard') }}" class="cc-btn-navy">
                            <i class="bi bi-grid-1x2"></i> Trainee Dashboard
                        </a>
                    @endif
                    
                    <form method="POST" action="{{ route('logout') }}" class="m-0 d-inline">
                        @csrf
                        <button type="submit" class="cc-btn-gold">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="cc-btn-outline-navy">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="cc-btn-gold">
                        <i class="bi bi-person-plus"></i> Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
