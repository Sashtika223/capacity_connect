@extends('layouts.trainer')

@section('trainer_content')
<div class="mb-4">
    <a href="{{ route('trainer.certifications.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Back to Certifications
    </a>
    <div class="d-flex align-items-center justify-content-between mt-2">
        <div>
            <h2 class="fw-bold mb-1 text-warning-emphasis">
                <i class="bi bi-arrow-repeat me-2"></i> Renewal Scenario Assessment: {{ $certification->course->title }}
            </h2>
            <p class="text-muted mb-0">Pass this periodic renewal assessment (Minimum 80%) to re-validate your trainer certification and extend teaching validity.</p>
        </div>
        <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill">
            <i class="bi bi-clock-history me-1"></i> Certification Renewal Session
        </span>
    </div>
</div>

<!-- Sticky 5-Minute Countdown Timer Banner -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-dark text-white p-3 sticky-top" style="top: 15px; z-index: 1020;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center">
            <span class="spinner-grow spinner-grow-sm text-warning me-2" role="status" aria-hidden="true"></span>
            <span class="fw-bold text-light">Renewal Assessment Active — 5-Minute Time Limit</span>
        </div>
        <div class="d-flex align-items-center bg-black bg-opacity-50 px-4 py-2 rounded-pill border border-warning">
            <i class="bi bi-clock-history me-2 text-warning fs-5"></i>
            <span class="text-white-50 me-2 small">Time Remaining:</span>
            <span id="timerDisplay" class="fw-black fs-4 text-warning font-monospace">05:00</span>
        </div>
    </div>
</div>

<form id="renewalForm" method="POST" action="{{ route('trainer.certifications.renewal.submit', $certification->id) }}">
    @csrf
    
    <div class="row g-4">
        @foreach($questions as $index => $q)
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-warning text-dark rounded-circle me-3 fs-6 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                {{ $index + 1 }}
                            </span>
                            <h5 class="fw-bold text-dark mb-0">{{ $q['question'] }}</h5>
                        </div>

                        <div class="ps-md-5">
                            @foreach($q['options'] as $key => $opt)
                                <label class="border rounded-3 p-3 mb-2 bg-light-subtle hover-shadow d-flex align-items-center cursor-pointer w-100" style="cursor: pointer;">
                                    <input class="form-check-input flex-shrink-0 me-3 mt-0" type="radio" name="answers[{{ $q['id'] }}]" id="rq_{{ $q['id'] }}_{{ $key }}" value="{{ $key }}" style="width: 1.25em; height: 1.25em; cursor: pointer;">
                                    <span class="text-dark flex-grow-1 fs-6">
                                        <strong>{{ $key }}.</strong> {{ $opt }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm rounded-4 mt-4 bg-dark text-white p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-warning"><i class="bi bi-shield-lock-fill me-2"></i> Submit Certification Renewal Test</h5>
                <p class="mb-0 text-white-50">Passing this assessment will grant a new 1-year validity period for teaching {{ $certification->course->title }}.</p>
            </div>
            <button type="submit" id="submitBtn" class="btn btn-warning text-dark btn-lg rounded-pill px-5 fw-bold shadow">
                Submit Renewal Assessment
            </button>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let secondsLeft = 300; // 5 minutes
    const timerDisplay = document.getElementById('timerDisplay');
    const form = document.getElementById('renewalForm');
    let submitted = false;

    form.addEventListener('submit', function() {
        submitted = true;
    });

    const countdown = setInterval(function() {
        if (submitted) {
            clearInterval(countdown);
            return;
        }

        secondsLeft--;
        let minutes = Math.floor(secondsLeft / 60);
        let seconds = secondsLeft % 60;
        
        let minStr = minutes < 10 ? '0' + minutes : minutes;
        let secStr = seconds < 10 ? '0' + seconds : seconds;

        timerDisplay.textContent = minStr + ':' + secStr;

        if (secondsLeft <= 60) {
            timerDisplay.classList.remove('text-warning');
            timerDisplay.classList.add('text-danger');
        }

        if (secondsLeft <= 0) {
            clearInterval(countdown);
            timerDisplay.textContent = '00:00';
            submitted = true;
            form.noValidate = true;
            alert('Time limit of 5 minutes reached! Auto-submitting your renewal scenario assessment now.');
            form.submit();
        }
    }, 1000);
});
</script>
@endsection
