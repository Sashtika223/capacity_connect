@extends('layouts.admin')

@section('title', 'Training Impact Analysis')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold"><i class="bi bi-graph-up-arrow text-success me-2"></i> Training Impact Analysis</h2>
        <p class="text-muted mb-0">Measure the real-world effectiveness of your LMS and track Trainee capability improvements.</p>
    </div>
</div>

<!-- Primary KPIs -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary text-white position-relative overflow-hidden">
            <div class="position-absolute top-0 end-0 p-3 opacity-25">
                <i class="bi bi-lightning-charge-fill" style="font-size: 5rem;"></i>
            </div>
            <div class="card-body p-4 position-relative z-1">
                <h6 class="fw-bold text-uppercase mb-2 text-white-50">OEE Score</h6>
                <div class="display-5 fw-bold">{{ number_format($oee, 1) }}%</div>
                <div class="small mt-1 text-white-50">Overall Effectiveness</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-success border-5">
            <div class="card-body p-4 text-center">
                <h6 class="text-success fw-bold text-uppercase mb-2">Avg Score Lift</h6>
                <div class="display-5 fw-bold text-success">+{{ number_format($avgScoreLift, 1) }}%</div>
                <div class="text-muted small mt-1">Before vs After Assessment</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-info border-5">
            <div class="card-body p-4 text-center">
                <h6 class="text-info-emphasis fw-bold text-uppercase mb-2">Completion Rate</h6>
                <div class="display-5 fw-bold text-info-emphasis">{{ number_format($completionRate, 1) }}%</div>
                <div class="text-muted small mt-1">Courses Finished</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-primary border-5">
            <div class="card-body p-4 text-center">
                <h6 class="text-primary fw-bold text-uppercase mb-2">Trainee Participation</h6>
                <div class="display-5 fw-bold text-primary">{{ number_format($participationRate, 1) }}%</div>
                <div class="text-muted small mt-1">Active in LMS</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Analysis Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Effectiveness Breakdown</h5>
                <canvas id="impactChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Competency Translation -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-light">
            <div class="card-body p-4 d-flex flex-column justify-content-center text-center">
                <div class="mb-4">
                    <i class="bi bi-person-fill-up text-primary" style="font-size: 4rem;"></i>
                </div>
                <h3 class="fw-bold text-dark mb-2">{{ number_format($competencyLiftRate, 1) }}%</h3>
                <h6 class="fw-bold text-muted text-uppercase mb-3">Competency Lift Rate</h6>
                <p class="text-muted small mb-0">Percentage of active trainees who successfully completed a course mapping directly to their identified skill gaps.</p>
            </div>
        </div>
    </div>
</div>

<!-- Top Improvers Data Table -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Highest Demonstrable Improvements</h5>
        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Before vs After Assessment</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4">Trainee</th>
                        <th class="py-3 text-center">Courses Completed</th>
                        <th class="py-3 text-center">Net Score Lift</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(array_slice($traineeImprovements, 0, 10) as $data)
                    <tr>
                        <td class="px-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $data['user']->name }}</h6>
                                    <span class="text-muted small">{{ $data['user']->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark px-3">{{ $data['courses_completed'] }} Courses</span>
                        </td>
                        <td class="text-center">
                            <span class="fw-bold text-success"><i class="bi bi-arrow-up text-success"></i> {{ number_format($data['total_lift'], 1) }}%</span>
                        </td>
                        <td class="px-4 text-center">
                            <span class="badge bg-success rounded-pill">High Performer</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
                            No "Before vs After" improvement data available yet. Trainees need to attempt the same assessment multiple times to calculate lift.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('impactChart').getContext('2d');
        
        // We will pass the 3 main percentages to the chart
        const completion = {{ $completionRate }};
        const participation = {{ $participationRate }};
        const competencyLift = {{ $competencyLiftRate }};
        const scoreLift = {{ $avgScoreLift }};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Completion Rate', 'Participation Rate', 'Competency Lift', 'Avg Score Improvement'],
                datasets: [{
                    label: 'Impact Metrics (%)',
                    data: [completion, participation, competencyLift, scoreLift],
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.7)',
                        'rgba(13, 110, 253, 0.7)',
                        'rgba(13, 110, 253, 0.7)',
                        'rgba(25, 135, 84, 0.7)' // Success green for score lift
                    ],
                    borderColor: [
                        'rgba(13, 110, 253, 1)',
                        'rgba(13, 110, 253, 1)',
                        'rgba(13, 110, 253, 1)',
                        'rgba(25, 135, 84, 1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endsection
