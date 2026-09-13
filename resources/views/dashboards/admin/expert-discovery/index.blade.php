@extends('layouts.admin')

@section('title', 'Hidden Expert Discovery')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="fw-bold"><i class="bi bi-person-badge text-primary me-2"></i> Hidden Expert Discovery</h2>
        <p class="text-muted mb-0">AI-assisted identification of high-expertise employees based on performance, certifications, competencies and assessments.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

{{-- Search & Filter --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.expert-discovery.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-bold small text-muted text-uppercase">Search by Expertise</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-primary"></i></span>
                    <input type="text" name="q" class="form-control border-start-0 ps-0" placeholder="e.g. Flood Management, Cyclone Response..." value="{{ $query }}">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold small text-muted text-uppercase">Minimum Expert Score</label>
                <input type="range" name="min_score" class="form-range" min="0" max="100" step="10" value="{{ $minScore }}" oninput="document.getElementById('scoreLabel').innerText = this.value + '/100'">
                <small class="text-muted">Minimum: <strong id="scoreLabel">{{ $minScore }}/100</strong></small>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-4 w-100">Discover</button>
                <a href="{{ route('admin.expert-discovery.index') }}" class="btn btn-outline-secondary rounded-pill px-3">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Results --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">
        @if($query)
            Results for "<span class="text-primary">{{ $query }}</span>"
        @else
            All Discovered Experts
        @endif
        <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-2 fs-6">{{ count($results) }} found</span>
    </h5>
</div>

@forelse($results as $result)
@php
    $user    = $result['user'];
    $profile = $result['profile'];
    $score   = $result['score'];
    $bd      = $result['breakdown'];
    $color   = $score >= 70 ? 'success' : ($score >= 50 ? 'warning' : ($score >= 30 ? 'info' : 'secondary'));
@endphp
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="row align-items-start g-4">

            {{-- Profile --}}
            <div class="col-md-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:56px;height:56px;font-size:1.4rem;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $user->name }}
                            @if($user->expert_verified)
                                <i class="bi bi-patch-check-fill text-primary ms-1" title="Verified Expert"></i>
                            @endif
                            @if($user->in_knowledge_repo)
                                <i class="bi bi-database-fill text-success ms-1" title="In Knowledge Repository"></i>
                            @endif
                        </h5>
                        <span class="badge bg-light text-dark border text-capitalize">{{ $user->role }}</span>
                        @if($user->expert_role)
                            <span class="badge bg-primary-subtle text-primary border ms-1 text-capitalize">{{ $user->expert_role }}</span>
                        @endif
                    </div>
                </div>
                <div class="text-muted small">
                    <div><i class="bi bi-briefcase me-2"></i>{{ $profile->designation ?? 'N/A' }}</div>
                    <div><i class="bi bi-building me-2"></i>{{ $profile->department ?? 'N/A' }}</div>
                </div>
            </div>

            {{-- Score --}}
            <div class="col-md-2 text-center">
                <div class="fw-bold text-muted small text-uppercase mb-2">Expert Score</div>
                <div class="display-5 fw-bold text-{{ $color }}">{{ $score }}</div>
                <div class="text-muted small">/100</div>
                <div class="progress mt-2 rounded-pill" style="height:8px;">
                    <div class="progress-bar bg-{{ $color }}" style="width:{{ $score }}%"></div>
                </div>
                <div class="mt-2">
                    <span class="badge bg-{{ $color }}-subtle text-{{ $color }} rounded-pill border border-{{ $color }} small">
                        {{ $score >= 70 ? 'High Expertise' : ($score >= 50 ? 'Moderate Expertise' : 'Emerging Expert') }}
                    </span>
                </div>
            </div>

            {{-- Expertise Areas --}}
            <div class="col-md-3">
                <div class="fw-bold text-muted small text-uppercase mb-2">Expertise Areas</div>
                @if(count($result['expertise_areas']) > 0)
                    @foreach($result['expertise_areas'] as $area)
                        <span class="badge border rounded-pill mb-1 px-2 py-1
                            {{ $area['level'] === 'Expert' ? 'border-success text-success bg-success-subtle' : 'border-primary text-primary bg-primary-subtle' }}">
                            {{ $area['name'] }} — {{ $area['level'] }}
                        </span><br>
                    @endforeach
                @else
                    <span class="text-muted small">No advanced competencies mapped</span>
                @endif
                <div class="mt-2 text-muted small">
                    <i class="bi bi-lightbulb-fill text-warning me-1"></i>
                    <strong>Recommended Role:</strong> {{ $result['recommended_role'] }}
                </div>
            </div>

            {{-- Evidence --}}
            <div class="col-md-4">
                <div class="fw-bold text-muted small text-uppercase mb-2">Evidence / Reason</div>
                <div class="bg-light rounded-3 p-3 small mb-3" style="max-height:120px; overflow-y:auto;">
                    @if(count($result['evidence']) > 0)
                        <ul class="mb-0 text-muted ps-3">
                            @foreach($result['evidence'] as $ev)
                                <li>{{ $ev }}</li>
                            @endforeach
                        </ul>
                    @else
                        <span class="text-muted">No specific evidence signals found.</span>
                    @endif
                </div>

                {{-- Admin Actions --}}
                <div class="d-flex flex-wrap gap-2">
                    @if(!$user->expert_verified)
                        <form action="{{ route('admin.expert-discovery.verify', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">
                                <i class="bi bi-patch-check me-1"></i> Verify Expert
                            </button>
                        </form>
                    @else
                        <span class="btn btn-sm btn-success rounded-pill px-3 disabled opacity-75"><i class="bi bi-patch-check-fill me-1"></i> Verified</span>
                    @endif

                    <div class="dropdown d-inline">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle rounded-pill px-3" data-bs-toggle="dropdown">
                            <i class="bi bi-person-plus me-1"></i> Assign Role
                        </button>
                        <ul class="dropdown-menu shadow">
                            <li>
                                <form action="{{ route('admin.expert-discovery.assign-role', $user->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="expert_role" value="trainer">
                                    <button type="submit" class="dropdown-item"><i class="bi bi-person-badge me-2 text-primary"></i> Assign as Trainer</button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('admin.expert-discovery.assign-role', $user->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="expert_role" value="mentor">
                                    <button type="submit" class="dropdown-item"><i class="bi bi-mortarboard me-2 text-success"></i> Assign as Mentor</button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    @if(!$user->in_knowledge_repo)
                        <form action="{{ route('admin.expert-discovery.add-to-repo', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                <i class="bi bi-database me-1"></i> Add to Repo
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@empty
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body text-center py-5">
        <i class="bi bi-person-slash text-muted" style="font-size:3rem;"></i>
        <h5 class="fw-bold text-muted mt-3">No Experts Discovered</h5>
        <p class="text-muted">Assign competencies and enroll trainees in courses to generate expert profiles. Try lowering the minimum score filter.</p>
    </div>
</div>
@endforelse

@endsection
