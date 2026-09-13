@extends('layouts.app')

@section('title', 'My Competencies')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="fw-bold display-6 mb-3">My Competency Profile</h1>
            <p class="text-muted lead">Track your skill levels, identify gaps, and find the right courses to grow.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Radar Chart -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-4">Skill Radar</h5>
                    @if($competencies->count() > 0)
                        <canvas id="competencyRadarChart"></canvas>
                    @else
                        <div class="py-5 text-muted">
                            <i class="bi bi-radar fs-1 d-block mb-3"></i>
                            No competencies mapped to your profile yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Skill Gaps -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Identified Skill Gaps</h5>
                    @if(count($gaps) > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Competency</th>
                                        <th>Current</th>
                                        <th>Required</th>
                                        <th>Gap</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($gaps as $gap)
                                    <tr>
                                        <td class="fw-medium">{{ $gap['competency']->name }}</td>
                                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $levelLabels[$gap['competency']->pivot->current_level] }}</span></td>
                                        <td><span class="badge bg-primary-subtle text-primary">{{ $levelLabels[$gap['competency']->pivot->required_level] }}</span></td>
                                        <td>
                                            <span class="text-danger fw-bold"><i class="bi bi-arrow-down text-danger"></i> -{{ $gap['gap'] }} Levels</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-success border-0 rounded-3 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                            <div>
                                <strong>Excellent!</strong> You have no identified skill gaps. You meet or exceed all required competency levels.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">Recommended Courses to Close Gaps</h3>
            @if(count($gaps) > 0)
                @if($recommendedCourses->count() > 0)
                    <div class="row g-4">
                        @foreach($recommendedCourses as $course)
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100 course-card">
                                @if($course->thumbnail)
                                    <img src="{{ Storage::url($course->thumbnail) }}" class="card-img-top rounded-top-4 object-fit-cover" height="200" alt="{{ $course->title }}">
                                @else
                                    <div class="card-img-top rounded-top-4 bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="bi bi-image text-muted fs-1"></i>
                                    </div>
                                @endif
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-2">{{ $course->title }}</h5>
                                    
                                    <div class="mb-3">
                                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Provides Skills:</span>
                                        @foreach($course->competencies as $courseComp)
                                            @php
                                                $isGapMatch = collect($gaps)->pluck('competency.id')->contains($courseComp->id);
                                            @endphp
                                            <span class="badge {{ $isGapMatch ? 'bg-primary' : 'bg-light text-dark' }} rounded-pill mb-1">
                                                {{ $courseComp->name }} ({{ $levelLabels[$courseComp->pivot->level] }})
                                            </span>
                                        @endforeach
                                    </div>
                                    
                                    <a href="{{ route('trainee.courses.show', $course->id) }}" class="btn btn-outline-primary rounded-pill w-100">View Course</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info border-0 rounded-3">
                        <i class="bi bi-info-circle me-2"></i> Currently, no active courses perfectly match your specific skill gaps. Check back later!
                    </div>
                @endif
            @else
                <div class="text-muted">
                    No recommendations needed right now. Explore the course catalog to learn new skills!
                </div>
            @endif
        </div>
    </div>
</div>

@if($competencies->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('competencyRadarChart').getContext('2d');
        
        const labels = {!! json_encode($competencies->pluck('name')) !!};
        const currentData = {!! json_encode($competencies->pluck('pivot.current_level')) !!};
        const requiredData = {!! json_encode($competencies->pluck('pivot.required_level')) !!};

        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Current Level',
                        data: currentData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2
                    },
                    {
                        label: 'Required Level',
                        data: requiredData,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2
                    }
                ]
            },
            options: {
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
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
