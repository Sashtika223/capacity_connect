@extends('layouts.admin')

@section('title', 'Admin Dashboard - Overview')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Platform Overview</h2>
    <div class="text-muted small">Live Metrics</div>
</div>

<!-- Key Metrics Row 1 -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary bg-gradient text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-white-50 mb-2 text-uppercase fw-bold small">Total Users</h6>
                        <h2 class="mb-0 fw-bold">{{ number_format($totalUsers) }}</h2>
                        <div class="small mt-2">
                            <span class="me-2"><i class="bi bi-mortarboard-fill"></i> {{ number_format($trainees) }} Trainees</span>
                            <span><i class="bi bi-person-badge-fill"></i> {{ number_format($trainers) }} Trainers</span>
                        </div>
                    </div>
                    <div class="p-3 bg-white bg-opacity-25 rounded-3">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-success bg-gradient text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-white-50 mb-2 text-uppercase fw-bold small">Total Courses</h6>
                        <h2 class="mb-0 fw-bold">{{ number_format($totalCourses) }}</h2>
                        <div class="small mt-2">
                            <span><i class="bi bi-check-circle-fill"></i> {{ number_format($activeCourses) }} Active</span>
                        </div>
                    </div>
                    <div class="p-3 bg-white bg-opacity-25 rounded-3">
                        <i class="bi bi-journal-album fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-warning bg-gradient text-dark">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-dark mb-2 text-uppercase fw-bold small opacity-75">Enrollments</h6>
                        <h2 class="mb-0 fw-bold">{{ number_format($totalEnrollments) }}</h2>
                        <div class="small mt-2 opacity-75">
                            <span><i class="bi bi-flag-fill"></i> {{ number_format($completedCourses) }} Completed ({{ $completionRate }}%)</span>
                        </div>
                    </div>
                    <div class="p-3 bg-dark bg-opacity-10 rounded-3">
                        <i class="bi bi-person-plus-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-info bg-gradient text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-white-50 mb-2 text-uppercase fw-bold small">Certifications</h6>
                        <h2 class="mb-0 fw-bold">{{ number_format($totalCertificates) }}</h2>
                        <div class="small mt-2">
                            <span><i class="bi bi-star-fill"></i> Avg Score: {{ number_format($averageScore, 1) }}%</span>
                        </div>
                    </div>
                    <div class="p-3 bg-white bg-opacity-25 rounded-3">
                        <i class="bi bi-patch-check-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- User Growth Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold mb-0">Platform Growth</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="growthChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Pass/Fail Ratio -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold mb-0">Assessment Performance</h5>
            </div>
            <div class="card-body p-4 d-flex justify-content-center align-items-center">
                <canvas id="performanceChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Enrollments vs Completions -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold mb-0">Learning Activity (Last 6 Months)</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="activityChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Shared Chart Options
    const commonOptions = {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        },
        scales: {
            y: { beginAtZero: true }
        }
    };

    // Data passed from controller
    const labels = {!! json_encode($months->reverse()->values()) !!};
    
    // 1. Growth Chart
    const growthCtx = document.getElementById('growthChart').getContext('2d');
    new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'New Users',
                data: {!! json_encode($userGrowth->reverse()->values()) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: commonOptions
    });

    // 2. Activity Chart (Enrollments vs Completions vs Certificates)
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    new Chart(activityCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Enrollments',
                    data: {!! json_encode($enrollmentChart->reverse()->values()) !!},
                    backgroundColor: '#ffc107',
                    borderRadius: 4
                },
                {
                    label: 'Completions',
                    data: {!! json_encode($completionChart->reverse()->values()) !!},
                    backgroundColor: '#198754',
                    borderRadius: 4
                },
                {
                    label: 'Certifications',
                    data: {!! json_encode($certificatesChart->reverse()->values()) !!},
                    backgroundColor: '#0dcaf0',
                    borderRadius: 4
                }
            ]
        },
        options: commonOptions
    });

    // 3. Performance Chart (Doughnut)
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    new Chart(performanceCtx, {
        type: 'doughnut',
        data: {
            labels: ['Passed', 'Failed'],
            datasets: [{
                data: [{{ $passedAssessments }}, {{ $failedAssessments }}],
                backgroundColor: ['#198754', '#dc3545'],
                borderWidth: 0
            }]
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