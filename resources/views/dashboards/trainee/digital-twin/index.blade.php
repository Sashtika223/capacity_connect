@extends('layouts.trainee')

@section('title', 'Competency Digital Twin')

@section('trainee_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="bi bi-person-bounding-box text-primary me-2"></i> Competency Digital Twin</h2>
</div>

<!-- Header Profile Summary -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary text-white overflow-hidden position-relative">
    <div class="position-absolute top-0 end-0 p-4 opacity-25">
        <i class="bi bi-fingerprint" style="font-size: 8rem;"></i>
    </div>
    <div class="card-body p-4 p-md-5 position-relative z-1">
        <div class="row align-items-center">
            <div class="col-auto">
                @if($profile && $profile->photo)
                    <img src="{{ Storage::url($profile->photo) }}" class="rounded-circle border border-4 border-white shadow-sm object-fit-cover" width="100" height="100" alt="Profile">
                @else
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 100px; height: 100px; font-size: 2.5rem;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                @endif
            </div>
            <div class="col">
                <h3 class="fw-bold mb-1">{{ $user->name }}</h3>
                <p class="mb-0 fs-5 text-white-50">{{ $profile->designation ?? 'Trainee' }} | {{ $profile->department ?? 'General Dept' }}</p>
                <div class="mt-3 d-flex flex-wrap gap-2">
                    <span class="badge bg-white text-primary rounded-pill px-3 py-2">
                        <i class="bi bi-journal-check me-1"></i> {{ $completedCourses->count() }} Courses Completed
                    </span>
                    <span class="badge bg-white text-primary rounded-pill px-3 py-2">
                        <i class="bi bi-award me-1"></i> {{ $certificates->count() }} Certifications
                    </span>
                    <span class="badge bg-white text-primary rounded-pill px-3 py-2">
                        <i class="bi bi-lightning-charge me-1"></i> {{ count($strengths) }} Key Strengths
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Visual Radar Map -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 text-center">
                <h5 class="fw-bold mb-4">Competency Map</h5>
                @if($competencies->count() > 0)
                    <canvas id="twinRadarChart" style="max-height: 400px;"></canvas>
                @else
                    <div class="py-5 text-muted">
                        <i class="bi bi-radar fs-1 d-block mb-3"></i>
                        No competencies mapped to your profile yet.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Strengths & Gaps Snapshot -->
    <div class="col-lg-5">
        <div class="row g-4 h-100">
            <!-- Strengths -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-success-subtle border-start border-success border-5">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-success mb-3"><i class="bi bi-star-fill me-2"></i> Top Strengths</h6>
                        @if(count($strengths) > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(array_slice($strengths, 0, 5) as $strength)
                                    <span class="badge bg-success rounded-pill p-2 px-3">
                                        {{ $strength['competency']->name }} 
                                        <span class="ms-1 opacity-75">({{ $levelLabels[$strength['competency']->pivot->current_level] }})</span>
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0 small">No strengths identified above required levels yet.</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Gaps -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-warning-subtle border-start border-warning border-5">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-warning-emphasis mb-3"><i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i> Skill Gaps</h6>
                        @if(count($gaps) > 0)
                            <ul class="list-group list-group-flush bg-transparent">
                                @foreach(array_slice($gaps, 0, 3) as $gap)
                                <li class="list-group-item bg-transparent px-0 py-2 d-flex justify-content-between align-items-center border-warning-subtle">
                                    <span class="fw-medium text-dark">{{ $gap['competency']->name }}</span>
                                    <span class="badge bg-warning text-dark rounded-pill">Needs {{ $levelLabels[$gap['competency']->pivot->required_level] }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @if(count($gaps) > 3)
                                <div class="mt-2 text-end">
                                    <small class="text-muted">+{{ count($gaps) - 3 }} more gap(s)</small>
                                </div>
                            @endif
                        @else
                            <p class="text-success mb-0 fw-medium"><i class="bi bi-check-circle me-1"></i> All required competencies met!</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Training Timeline -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Recent Learning Milestones</h5>
                <div class="timeline px-2">
                    @forelse($certificates->take(4) as $cert)
                    <div class="d-flex mb-4 position-relative">
                        <div class="me-3 mt-1">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                <i class="bi bi-award-fill"></i>
                            </div>
                        </div>
                        <div class="pb-3 border-bottom w-100">
                            <h6 class="fw-bold mb-1">Earned Certificate: {{ $cert->course->title }}</h6>
                            <p class="text-muted small mb-1">Completed with score {{ $cert->score }}%</p>
                            <span class="badge bg-light text-dark">{{ $cert->issued_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
                            No certificates earned yet. Complete courses to build your timeline!
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-light">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4"><i class="bi bi-lightbulb-fill text-warning me-2"></i> Recommended for You</h5>
                
                @if(count($gaps) > 0 && $recommendedCourses->count() > 0)
                    <p class="small text-muted mb-4">Based on your identified skill gaps, we recommend these courses:</p>
                    <div class="d-flex flex-column gap-3">
                        @foreach($recommendedCourses as $course)
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-1 text-truncate">{{ $course->title }}</h6>
                                <p class="text-muted small mb-2 text-truncate">{{ $course->category->name ?? 'General' }}</p>
                                <a href="{{ route('trainee.courses.show', $course->id) }}" class="btn btn-sm btn-outline-primary rounded-pill w-100">Enroll Now</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @elseif(count($gaps) == 0)
                    <div class="text-center py-4">
                        <i class="bi bi-trophy fs-1 text-warning d-block mb-3"></i>
                        <p class="text-muted mb-0">You have no skill gaps! Explore the catalog to learn new things.</p>
                        <a href="{{ route('trainee.courses') }}" class="btn btn-primary rounded-pill mt-3 px-4">Browse Catalog</a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">No active courses currently match your exact skill gaps.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($competencies->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('twinRadarChart').getContext('2d');
        
        const labels = {!! json_encode($competencies->pluck('name')) !!};
        const currentData = {!! json_encode($competencies->pluck('pivot.current_level')) !!};
        const requiredData = {!! json_encode($competencies->pluck('pivot.required_level')) !!};

        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Current Capabilities',
                        data: currentData,
                        backgroundColor: 'rgba(54, 162, 235, 0.4)', // More solid fill for Digital Twin
                        borderColor: 'rgba(54, 162, 235, 1)',
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: true
                    },
                    {
                        label: 'Required Target',
                        data: requiredData,
                        backgroundColor: 'transparent',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderDash: [5, 5], // Dashed line for target
                        pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: {
                            display: true
                        },
                        suggestedMin: 0,
                        suggestedMax: 4,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                const map = {0: 'None', 1: 'Beginner', 2: 'Intermediate', 3: 'Advanced', 4: 'Expert'};
                                return map[value] || '';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
