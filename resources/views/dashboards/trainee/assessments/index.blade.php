@extends('layouts.trainee')

@section('trainee_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">My Assessments</h2>
</div>

<div class="row">
    @forelse($assessments as $assessment)
        @php
            $attempt = $assessment->attempts->first();
            $status = $attempt ? $attempt->status : 'pending';
        @endphp
        
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-primary">{{ $assessment->course->course_code }}</span>
                        @if($status == 'completed')
                            <span class="badge {{ $attempt->passed ? 'bg-success' : 'bg-danger' }}">
                                {{ $attempt->passed ? 'Passed' : 'Failed' }}
                            </span>
                        @elseif($status == 'in-progress')
                            <span class="badge bg-warning text-dark">In Progress</span>
                        @else
                            <span class="badge bg-secondary">Pending</span>
                        @endif
                    </div>
                    
                    <h5 class="fw-bold text-primary mt-2">{{ $assessment->title }}</h5>
                    <p class="text-muted small mb-2">{{ $assessment->subject ?? 'General Assessment' }}</p>
                    
                    <div class="d-flex gap-3 text-muted small mt-3">
                        <span><i class="bi bi-clock"></i> {{ $assessment->duration }} mins</span>
                        <span><i class="bi bi-check-circle"></i> Pass: {{ $assessment->passing_score }}%</span>
                    </div>

                    @if($assessment->end_date)
                        <div class="small text-danger mt-2">
                            <i class="bi bi-calendar-x"></i> Deadline: {{ $assessment->end_date->format('M d, Y h:i A') }}
                        </div>
                    @endif

                    <div class="mt-4">
                        @if($status == 'completed')
                            <a href="{{ route('trainee.assessments.result', $attempt->id) }}" class="btn btn-outline-success w-100">View Result</a>
                        @else
                            <a href="{{ route('trainee.assessments.show', $assessment->id) }}" class="btn btn-primary w-100">
                                {{ $status == 'in-progress' ? 'Resume Assessment' : 'Start Assessment' }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <h5 class="text-muted">No pending assessments at the moment.</h5>
        </div>
    @endforelse
</div>
@endsection
