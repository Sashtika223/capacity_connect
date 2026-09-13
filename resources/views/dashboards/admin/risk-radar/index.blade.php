@extends('layouts.admin')

@section('title', 'Competency Risk Radar')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold"><i class="bi bi-exclamation-triangle text-warning me-2"></i> Competency Risk Radar</h2>
        <p class="text-muted mb-0">Identify organizational skill shortages and mitigate talent risks.</p>
    </div>
</div>

<!-- Key Metrics -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-danger-subtle border-start border-danger border-5">
            <div class="card-body p-4 text-center">
                <h6 class="text-danger fw-bold text-uppercase mb-2">Critical Risk</h6>
                <div class="display-5 fw-bold text-danger">{{ $metrics['critical_risk'] }}</div>
                <div class="text-muted small mt-1">Competencies > 50% Shortage</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-warning-subtle border-start border-warning border-5">
            <div class="card-body p-4 text-center">
                <h6 class="text-warning-emphasis fw-bold text-uppercase mb-2">High Risk</h6>
                <div class="display-5 fw-bold text-warning-emphasis">{{ $metrics['high_risk'] }}</div>
                <div class="text-muted small mt-1">Competencies > 20% Shortage</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-info-subtle border-start border-info border-5">
            <div class="card-body p-4 text-center">
                <h6 class="text-info-emphasis fw-bold text-uppercase mb-2">Medium Risk</h6>
                <div class="display-5 fw-bold text-info-emphasis">{{ $metrics['medium_risk'] }}</div>
                <div class="text-muted small mt-1">Competencies > 0% Shortage</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-success-subtle border-start border-success border-5">
            <div class="card-body p-4 text-center">
                <h6 class="text-success fw-bold text-uppercase mb-2">Low Risk</h6>
                <div class="display-5 fw-bold text-success">{{ $metrics['low_risk'] }}</div>
                <div class="text-muted small mt-1">Fully Staffed Capabilities</div>
            </div>
        </div>
    </div>
</div>

<!-- Visualizations -->
<div class="row g-4 mb-5">
    <!-- Top Risks Bar Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Capacity vs Requirement (Top Risks)</h5>
                <canvas id="riskBarChart" style="max-height: 350px;"></canvas>
            </div>
        </div>
    </div>
    <!-- Organizational Shape Radar -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 text-center">
                <h5 class="fw-bold mb-4">Organizational Shape</h5>
                <canvas id="riskRadarChart" style="max-height: 350px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Data Table -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4">Competency</th>
                        <th class="py-3 text-center">Required Capacity</th>
                        <th class="py-3 text-center">Current Capacity</th>
                        <th class="py-3 text-center">Organizational Gap</th>
                        <th class="py-3 text-center">Risk Tier</th>
                        <th class="py-3 px-4">Recommended Training</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riskData as $data)
                    <tr>
                        <td class="px-4 fw-medium">{{ $data['competency']->name }}</td>
                        <td class="text-center">{{ $data['required_capacity'] }}</td>
                        <td class="text-center">{{ $data['current_capacity'] }}</td>
                        <td class="text-center">
                            @if($data['gap'] > 0)
                                <span class="text-danger fw-bold"><i class="bi bi-arrow-down text-danger"></i> -{{ $data['gap'] }}</span>
                            @else
                                <span class="text-success fw-bold"><i class="bi bi-check2"></i> Met</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $data['risk_color'] }} rounded-pill px-3 text-uppercase shadow-sm">
                                {{ $data['risk_level'] }}
                            </span>
                        </td>
                        <td class="px-4">
                            @if($data['gap'] > 0)
                                @if($data['recommended_courses']->count() > 0)
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle rounded-pill w-100" type="button" data-bs-toggle="dropdown">
                                            View {{ $data['recommended_courses']->count() }} Courses
                                        </button>
                                        <ul class="dropdown-menu shadow">
                                            @foreach($data['recommended_courses'] as $course)
                                                <li><a class="dropdown-item text-truncate" style="max-width: 250px;" href="{{ route('admin.courses.edit', $course->id) }}">{{ $course->title }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <span class="text-danger small"><i class="bi bi-exclamation-circle"></i> No courses map to this!</span>
                                @endif
                            @else
                                <span class="text-muted small">No action needed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">No competencies have been mapped to users yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js Logic -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prepare Data for top 7 risks
        const allData = {!! json_encode($riskData) !!};
        const topRisks = allData.slice(0, 7);
        
        const labels = topRisks.map(item => item.competency.name);
        const reqData = topRisks.map(item => item.required_capacity);
        const curData = topRisks.map(item => item.current_capacity);

        // Bar Chart
        const barCtx = document.getElementById('riskBarChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Required Capacity',
                        data: reqData,
                        backgroundColor: 'rgba(220, 53, 69, 0.7)', // Danger red
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Current Capacity',
                        data: curData,
                        backgroundColor: 'rgba(13, 110, 253, 0.7)', // Primary blue
                        borderColor: 'rgba(13, 110, 253, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Radar Chart
        const radarCtx = document.getElementById('riskRadarChart').getContext('2d');
        new Chart(radarCtx, {
            type: 'radar',
            data: {
                labels: labels.length > 0 ? labels : ['None'],
                datasets: [
                    {
                        label: 'Required',
                        data: reqData,
                        backgroundColor: 'transparent',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderDash: [5, 5],
                        borderWidth: 2
                    },
                    {
                        label: 'Current',
                        data: curData,
                        backgroundColor: 'rgba(13, 110, 253, 0.2)',
                        borderColor: 'rgba(13, 110, 253, 1)',
                        borderWidth: 2,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>
@endsection