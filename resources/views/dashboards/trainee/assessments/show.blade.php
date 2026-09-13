@extends('layouts.app')

@section('title', 'Assessment: ' . $assessment->title)

@section('content')
<div class="container-fluid bg-light py-3 border-bottom sticky-top shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold text-primary mb-0">{{ $assessment->title }}</h5>
            <small class="text-muted">{{ $assessment->course->title }}</small>
        </div>
        <div class="text-end">
            <div class="fs-4 fw-bold text-danger" id="timerDisplay">--:--</div>
            <small class="text-muted">Time Remaining</small>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 bg-primary bg-opacity-10 text-primary rounded-4">
                    <h5 class="fw-bold mb-2">Instructions</h5>
                    <p class="mb-0">{{ $assessment->instructions ?? 'Please answer all questions carefully. Do not refresh this page.' }}</p>
                </div>
            </div>

            <form id="assessmentForm" method="POST" action="{{ route('trainee.assessments.submit', $assessment->id) }}">
                @csrf
                
                @foreach($assessment->questions as $index => $question)
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold mb-0">Question {{ $index + 1 }}</h5>
                                <span class="badge bg-light text-dark border">{{ $question->marks }} Marks</span>
                            </div>
                            
                            <p class="fs-5 mb-4">{{ $question->question_text }}</p>
                            
                            <div class="d-flex flex-column gap-3">
                                @foreach($question->options as $option)
                                    <label class="border rounded-3 p-3 option-label cursor-pointer" style="cursor: pointer;">
                                        <div class="form-check d-flex align-items-center m-0">
                                            <input class="form-check-input me-3 fs-5" type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}">
                                            <span class="fs-6">{{ $option->option_text }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="d-grid mt-5">
                    <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Are you sure you want to submit your assessment? You cannot change your answers after submission.')">Submit Assessment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .option-label:hover {
        background-color: #f8f9fa;
        border-color: #0d6efd !important;
    }
</style>

<script>
    // Timer Logic
    const startTime = new Date("{{ $attempt->start_time }}").getTime();
    const durationMs = {{ $assessment->duration }} * 60 * 1000;
    const endTime = startTime + durationMs;

    function updateTimer() {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance <= 0) {
            document.getElementById("timerDisplay").innerHTML = "00:00";
            document.getElementById("assessmentForm").submit();
            return;
        }

        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("timerDisplay").innerHTML = 
            (minutes < 10 ? "0" : "") + minutes + ":" + 
            (seconds < 10 ? "0" : "") + seconds;
    }

    setInterval(updateTimer, 1000);
    updateTimer();
</script>
@endsection