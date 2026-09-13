@extends('layouts.trainee')

@section('trainee_content')
<div class="mb-4">
    <a href="{{ route('trainee.assessments') }}" class="text-decoration-none">&larr; Back to Assessments</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 text-center overflow-hidden">
            <div class="bg-{{ $attempt->passed ? 'success' : 'danger' }} bg-opacity-10 py-5 px-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm mb-4" style="width: 100px; height: 100px;">
                    <i class="bi {{ $attempt->passed ? 'bi-trophy-fill text-success' : 'bi-x-circle-fill text-danger' }}" style="font-size: 3rem;"></i>
                </div>
                
                <h2 class="fw-bold text-dark mb-2">{{ $attempt->passed ? 'Congratulations!' : 'Assessment Failed' }}</h2>
                <p class="text-muted fs-5 mb-0">You scored <strong>{{ $attempt->percentage }}%</strong> on <strong>{{ $attempt->assessment->title }}</strong></p>
                <div class="small text-muted mt-2">Passing score: {{ $attempt->assessment->passing_score }}%</div>
            </div>
            
            <div class="card-body p-4 p-md-5 text-start">
                <h5 class="fw-bold mb-4">Performance Breakdown</h5>
                
                <div class="row g-4 mb-5 text-center">
                    <div class="col-6 col-sm-3">
                        <div class="p-3 bg-light rounded-3">
                            <h3 class="fw-bold mb-1">{{ $attempt->score }}</h3>
                            <small class="text-muted">Total Score</small>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-3 bg-light rounded-3">
                            <h3 class="fw-bold mb-1">{{ $attempt->answers->where('is_correct', true)->count() }}</h3>
                            <small class="text-muted">Correct Answers</small>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-3 bg-light rounded-3">
                            <h3 class="fw-bold mb-1">{{ $attempt->answers->where('is_correct', false)->count() }}</h3>
                            <small class="text-muted">Wrong Answers</small>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-3 bg-light rounded-3">
                            @php
                                $timeTaken = $attempt->end_time ? $attempt->end_time->diffInMinutes($attempt->start_time) : 0;
                            @endphp
                            <h3 class="fw-bold mb-1">{{ $timeTaken }}m</h3>
                            <small class="text-muted">Time Taken</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <a href="{{ route('trainee.courses.show', $attempt->assessment->course_id) }}" class="btn btn-outline-primary px-4">Return to Course</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
