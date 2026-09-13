@extends('layouts.admin')

@section('title', 'What-If Capacity Simulator')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold"><i class="bi bi-magic text-primary me-2"></i> Capacity Simulator</h2>
        <p class="text-muted mb-0">Run what-if training scenarios to predict capacity improvements before committing resources.</p>
    </div>
</div>

<div class="alert alert-warning border-warning border-start border-5 rounded-4 shadow-sm mb-4 d-flex gap-3 align-items-start">
    <i class="bi bi-exclamation-triangle-fill text-warning fs-4 mt-1 flex-shrink-0"></i>
    <div>
        <strong>Model-Based Estimates Only.</strong> All projections displayed below are rule-based estimates for planning purposes. They do <em>not</em> modify any real employee competency data and should not be treated as guaranteed outcomes.
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="row g-4 mb-5">
    {{-- Simulator Engine --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-sliders me-2 text-primary"></i> Simulation Engine</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.capacity-simulator.store') }}" method="POST" id="simulationForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Scenario Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Q4 Cyclone Response Push" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Target Competency</label>
                        <select name="competency_id" class="form-select" required>
                            <option value="" disabled selected>Select Competency...</option>
                            @foreach($competencies as $comp)
                                <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <h6 class="fw-bold text-muted small text-uppercase mb-3">Current State</h6>
                    <div class="row g-3 mb-4 p-3 bg-light rounded-3 border">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Current Trained Employees</label>
                            <input type="number" name="current_employees" id="sim_current_emp" class="form-control" value="20" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Current Capacity (Points)</label>
                            <input type="number" name="current_capacity" id="sim_current_cap" class="form-control" value="20" min="0" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-muted">Required Capacity (Points)</label>
                            <input type="number" name="required_capacity" id="sim_req_cap" class="form-control" value="50" min="1" required>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary small text-uppercase mb-3">Training Intervention Variables</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Employees to Train</label>
                            <input type="number" name="employees_to_train" id="sim_train_emp" class="form-control border-primary" value="15" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Expected Lift per Employee</label>
                            <input type="number" step="0.1" name="expected_improvement" id="sim_lift" class="form-control border-primary" value="1.0" min="0.1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Training Duration (days)</label>
                            <input type="number" name="training_duration_days" id="sim_duration" class="form-control" value="30" min="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">No. of Sessions</label>
                            <input type="number" name="training_sessions" id="sim_sessions" class="form-control" value="5" min="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Trainers Required</label>
                            <input type="number" name="trainers_required" id="sim_trainers" class="form-control" value="2" min="1">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">
                            <i class="bi bi-play-circle me-2"></i> Run & Save Simulation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Live Results --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary text-white position-relative overflow-hidden">
            <div class="position-absolute top-0 end-0 p-4 opacity-10">
                <i class="bi bi-graph-up-arrow" style="font-size: 8rem;"></i>
            </div>
            <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-center position-relative z-1">
                <div class="mb-2 opacity-75 small text-uppercase fw-bold">⚡ Live Estimate Preview</div>
                <h5 class="fw-bold mb-4 opacity-90">Projected Outcome</h5>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="small opacity-75 fw-bold">Projected Capacity</div>
                        <div class="display-5 fw-bold" id="res_projected">35</div>
                    </div>
                    <div class="col-6">
                        <div class="small opacity-75 fw-bold">Remaining Gap</div>
                        <div class="display-5 fw-bold" id="res_gap">15</div>
                    </div>
                    <div class="col-6">
                        <div class="small opacity-75 fw-bold">Employees Improved</div>
                        <div class="display-5 fw-bold" id="res_improved">15</div>
                    </div>
                    <div class="col-6">
                        <div class="small opacity-75 fw-bold">Est. Training Hours</div>
                        <div class="display-5 fw-bold" id="res_hours">600</div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <div class="small opacity-75 fw-bold">Target Met</div>
                        <div class="fs-5 fw-bold" id="res_pct_text">70%</div>
                    </div>
                    <div class="progress rounded-pill bg-white bg-opacity-25" style="height:10px;">
                        <div class="progress-bar bg-white" id="res_pct_bar" style="width:70%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <div class="small opacity-75 fw-bold">Risk Reduction (Est.)</div>
                        <div class="fs-5 fw-bold" id="res_risk">0%</div>
                    </div>
                    <div class="progress rounded-pill bg-white bg-opacity-25" style="height:10px;">
                        <div class="progress-bar bg-success" id="res_risk_bar" style="width:0%"></div>
                    </div>
                </div>

                <p class="small opacity-50 mt-3 mb-0 fst-italic">* Estimates only. Actual results may vary based on trainer quality, learner engagement, and environment.</p>
            </div>
        </div>
    </div>
</div>

{{-- Chart for Saved Scenarios --}}
@if($simulations->count() > 0)
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom p-4">
        <h5 class="fw-bold mb-0"><i class="bi bi-bar-chart me-2 text-primary"></i> Scenarios — Capacity Comparison (Model-Based Estimates)</h5>
    </div>
    <div class="card-body p-4">
        <canvas id="simChart" style="max-height:300px;"></canvas>
    </div>
</div>
@endif

{{-- Saved Scenarios Table --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom p-4">
        <h5 class="fw-bold mb-0"><i class="bi bi-archive me-2 text-secondary"></i> Saved Scenarios</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4">Scenario</th>
                        <th class="py-3">Competency</th>
                        <th class="py-3 text-center">Trained</th>
                        <th class="py-3 text-center">Proj. Cap.</th>
                        <th class="py-3 text-center">Gap</th>
                        <th class="py-3 text-center">Hours</th>
                        <th class="py-3 text-center">Risk ↓</th>
                        <th class="py-3 text-center">Improvement</th>
                        <th class="py-3 px-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($simulations as $sim)
                    <tr>
                        <td class="px-4 fw-medium">{{ $sim->name }}</td>
                        <td>{{ $sim->competency->name ?? '—' }}</td>
                        <td class="text-center"><span class="badge bg-primary-subtle text-primary rounded-pill px-2">+{{ $sim->employees_to_train }}</span></td>
                        <td class="text-center fw-bold">{{ $sim->projected_capacity }} / {{ $sim->required_capacity }}</td>
                        <td class="text-center text-{{ $sim->remaining_gap > 0 ? 'danger' : 'success' }}">
                            {{ $sim->remaining_gap > 0 ? $sim->remaining_gap : '✓ Met' }}
                        </td>
                        <td class="text-center text-muted">{{ $sim->training_hours_total ?? '—' }}h</td>
                        <td class="text-center text-success">{{ $sim->risk_reduction_percentage ? number_format($sim->risk_reduction_percentage, 1).'%' : '—' }}</td>
                        <td class="text-center text-success">+{{ number_format($sim->improvement_percentage, 1) }}%</td>
                        <td class="px-4 text-end">
                            <form action="{{ route('admin.capacity-simulator.destroy', $sim->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Delete this scenario?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-5 text-muted">No saved scenarios yet. Run a simulation above!</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = {
        currentCap: document.getElementById('sim_current_cap'),
        reqCap:     document.getElementById('sim_req_cap'),
        trainEmp:   document.getElementById('sim_train_emp'),
        lift:       document.getElementById('sim_lift'),
        sessions:   document.getElementById('sim_sessions'),
    };

    const resProj    = document.getElementById('res_projected');
    const resGap     = document.getElementById('res_gap');
    const resImproved= document.getElementById('res_improved');
    const resHours   = document.getElementById('res_hours');
    const resPctText = document.getElementById('res_pct_text');
    const resPctBar  = document.getElementById('res_pct_bar');
    const resRisk    = document.getElementById('res_risk');
    const resRiskBar = document.getElementById('res_risk_bar');

    function calculate() {
        const cur  = parseFloat(inputs.currentCap.value) || 0;
        const req  = parseFloat(inputs.reqCap.value)     || 1;
        const emp  = parseFloat(inputs.trainEmp.value)   || 0;
        const lift = parseFloat(inputs.lift.value)       || 0;
        const sess = parseFloat(inputs.sessions.value)   || 1;

        const projected     = cur + (emp * lift);
        const gap           = Math.max(0, req - projected);
        const targetMetPct  = Math.min(100, (projected / req) * 100);
        const previousGap   = Math.max(0, req - cur);
        const riskReduction = previousGap > 0 ? Math.min(100, ((previousGap - gap) / previousGap) * 100) : 0;
        const hours         = emp * sess * 8;

        resProj.innerText    = projected.toFixed(1).replace('.0','');
        resGap.innerText     = gap === 0 ? '0 ✓' : gap.toFixed(1).replace('.0','');
        resImproved.innerText= emp;
        resHours.innerText   = hours;
        resPctText.innerText = targetMetPct.toFixed(1) + '%';
        resPctBar.style.width= targetMetPct + '%';
        resRisk.innerText    = riskReduction.toFixed(1) + '%';
        resRiskBar.style.width= riskReduction + '%';
    }

    Object.values(inputs).forEach(inp => { if(inp) inp.addEventListener('input', calculate); });
    calculate();

    // Saved scenarios chart
    @if($simulations->count() > 0)
    const simData = {!! json_encode($simulations->map(fn($s) => [
        'name' => $s->name,
        'current' => $s->current_capacity,
        'projected' => $s->projected_capacity,
        'required' => $s->required_capacity,
    ])) !!};
    const ctx = document.getElementById('simChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: simData.map(s => s.name),
            datasets: [
                { label: 'Current Capacity', data: simData.map(s => s.current),   backgroundColor: 'rgba(107,114,128,0.6)' },
                { label: 'Projected (Est.)', data: simData.map(s => s.projected), backgroundColor: 'rgba(13,110,253,0.6)' },
                { label: 'Required',          data: simData.map(s => s.required), backgroundColor: 'rgba(220,53,69,0.4)',  borderColor: 'rgba(220,53,69,1)', borderWidth: 2, type: 'line', fill: false },
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
    });
    @endif
});
</script>
@endsection
