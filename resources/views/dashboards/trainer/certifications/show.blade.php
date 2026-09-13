@extends('layouts.trainer')

@section('trainer_content')
<div class="mb-4">
    <a href="{{ route('trainer.certifications.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Back to Certifications
    </a>
</div>

<!-- Scorecard Result Banner -->
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
    <div class="card-body p-4 p-md-5 {{ $evaluation['passed'] ? 'bg-success text-white' : 'bg-danger text-white' }}">
        <div class="row align-items-center g-4">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <span class="badge {{ $evaluation['passed'] ? 'bg-white text-success' : 'bg-white text-danger' }} fs-6 px-3 py-2 rounded-pill fw-bold">
                        <i class="bi {{ $evaluation['passed'] ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                        {{ $evaluation['passed'] ? 'Assessment Passed' : 'Assessment Failed' }}
                    </span>
                    <span class="text-white-50 text-uppercase fw-semibold tracking-wider small">
                        {{ ucfirst($evaluation['type']) }} Scenario Evaluation
                    </span>
                </div>
                <h2 class="fw-bold mb-2">{{ $certification->course->title }}</h2>
                <p class="mb-0 text-white-50 fs-5">
                    @if($evaluation['passed'])
                        Congratulations! Your scenario assessment score of <strong>{{ $evaluation['score'] }}%</strong> meets institutional standards (80% minimum required).
                    @else
                        Your scenario assessment score of <strong>{{ $evaluation['score'] }}%</strong> did not meet the 80% passing threshold. Please review the detailed explanations below before retaking.
                    @endif
                </p>
            </div>
            <div class="col-md-4 text-md-end text-center">
                <div class="d-inline-flex flex-column align-items-center justify-content-center bg-white bg-opacity-10 p-4 rounded-4 border border-white border-opacity-25" style="min-width: 140px;">
                    <span class="display-4 fw-black text-white lh-1">{{ (int)$evaluation['score'] }}%</span>
                    <span class="small text-white-50 mt-1">Final Score</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Question-by-Question Evaluation & Explanations -->
<div class="mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="fw-bold text-dark mb-0">
            <i class="bi bi-file-earmark-text-fill text-primary me-2"></i> Auto-Evaluated Scenario Breakdown & Explanations
        </h4>
        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
            5 Questions Evaluated
        </span>
    </div>

    <div class="row g-4">
        @foreach($evaluation['questions'] as $q)
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden {{ $q['is_correct'] ? 'border-start border-success border-5' : 'border-start border-danger border-5' }}">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge {{ $q['is_correct'] ? 'bg-success text-white' : 'bg-danger text-white' }} rounded-circle fs-6 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                    {{ $loop->iteration }}
                                </span>
                                <h5 class="fw-bold text-dark mb-0">{{ $q['question'] }}</h5>
                            </div>
                            <div>
                                @if($q['is_correct'])
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold">
                                        <i class="bi bi-check-lg me-1"></i> Correct (+20%)
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-bold">
                                        <i class="bi bi-x-lg me-1"></i> Incorrect (0%)
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Options List -->
                        <div class="ps-md-5 mb-4">
                            <div class="row g-2">
                                @foreach($q['options'] as $key => $opt)
                                    @php
                                        $isSelected = ($q['selected'] === $key);
                                        $isCorrectOpt = ($q['correct'] === $key);
                                        
                                        $cardStyle = 'bg-light border-0';
                                        if ($isCorrectOpt) {
                                            $cardStyle = 'bg-success-subtle border border-success text-success fw-bold';
                                        } elseif ($isSelected && !$isCorrectOpt) {
                                            $cardStyle = 'bg-danger-subtle border border-danger text-danger text-decoration-line-through';
                                        }
                                    @endphp
                                    <div class="col-12">
                                        <div class="p-3 rounded-3 {{ $cardStyle }} d-flex align-items-center justify-content-between">
                                            <div>
                                                <strong class="me-2">{{ $key }}.</strong> {{ $opt }}
                                            </div>
                                            <div>
                                                @if($isCorrectOpt)
                                                    <span class="badge bg-success text-white rounded-pill px-2 py-1 small">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Correct Choice
                                                    </span>
                                                @endif
                                                @if($isSelected && !$isCorrectOpt)
                                                    <span class="badge bg-danger text-white rounded-pill px-2 py-1 small ms-1">
                                                        <i class="bi bi-x-circle-fill me-1"></i> Your Answer
                                                    </span>
                                                @elseif($isSelected && $isCorrectOpt)
                                                    <span class="badge bg-success-subtle text-success border border-success rounded-pill px-2 py-1 small ms-1">
                                                        <i class="bi bi-person-check-fill me-1"></i> Your Answer
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Explanation Callout -->
                        <div class="ps-md-5">
                            <div class="p-3 rounded-3 {{ $q['is_correct'] ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-danger bg-opacity-10 border border-danger border-opacity-25' }}">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi {{ $q['is_correct'] ? 'bi-patch-check-fill text-success' : 'bi-exclamation-triangle-fill text-danger' }} fs-5 me-2"></i>
                                    <strong class="{{ $q['is_correct'] ? 'text-success' : 'text-danger' }}">
                                        {{ $q['is_correct'] ? 'Why This Answer Is Correct:' : 'Why Answer Was Incorrect & Correct Solution:' }}
                                    </strong>
                                </div>
                                <p class="mb-0 text-dark small ps-4">
                                    {{ $q['explanation'] }}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mt-4">
    <a href="{{ route('trainer.certifications.index') }}" class="btn btn-dark rounded-pill px-4">
        <i class="bi bi-arrow-left me-2"></i> Return to Certifications
    </a>
    @if(!$evaluation['passed'])
        <a href="{{ route('trainer.certifications.test', $certification->id) }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow">
            <i class="bi bi-arrow-repeat me-2"></i> Retake Scenario Assessment
        </a>
    @endif
</div>
@endsection
