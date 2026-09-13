@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold text-dark mb-1"><i class="fas fa-headset text-danger mr-2"></i>Practical Disaster Response Drills</h1>
            <p class="text-muted mb-0">Test your decision-making and operational response in dynamic disaster scenario simulations.</p>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <!-- Active Practical Drills -->
        <div class="col-lg-8">
            <h5 class="font-weight-bold mb-3"><i class="fas fa-play-circle text-primary mr-2"></i>Available Response Drills</h5>
            <div class="row">
                @forelse($assessments as $assessment)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge badge-primary p-2"><i class="fas fa-bolt mr-1"></i> Interactive Drill</span>
                                    <small class="text-muted">{{ $assessment->nodes->count() }} Steps</small>
                                </div>
                                <h5 class="card-title font-weight-bold text-dark mt-2 mb-2">{{ $assessment->title }}</h5>
                                <p class="card-text text-muted small flex-grow-1">{{ Str::limit($assessment->description, 110) }}</p>
                                
                                @if($assessment->course)
                                    <div class="mb-3">
                                        <small class="text-muted"><i class="fas fa-book mr-1"></i> {{ $assessment->course->title }}</small>
                                    </div>
                                @endif

                                <form action="{{ route('trainee.practical.start', $assessment) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-block font-weight-bold">
                                        <i class="fas fa-gamepad mr-1"></i> Start Simulation
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light py-5 text-center border">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <p class="mb-0 text-muted">No published practical response drills are currently available.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- My Drill Attempts & History -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="card-title mb-0 font-weight-bold"><i class="fas fa-history mr-2"></i>My Drill History</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($myAttempts as $attempt)
                            <li class="list-group-item p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark small">{{ $attempt->assessment->title ?? 'Practical Drill' }}</strong>
                                    @if($attempt->status === 'completed')
                                        <span class="badge badge-success">Completed</span>
                                    @else
                                        <span class="badge badge-warning">In Progress</span>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small"><i class="fas fa-clock mr-1"></i> {{ $attempt->created_at->diffForHumans() }}</span>
                                    @if($attempt->status === 'completed')
                                        <a href="{{ route('trainee.practical.result', $attempt) }}" class="btn btn-xs btn-outline-info">
                                            Score: {{ $attempt->final_score }} pts
                                        </a>
                                    @else
                                        <a href="{{ route('trainee.practical.play', $attempt) }}" class="btn btn-xs btn-primary">
                                            Resume Drill
                                        </a>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">
                                No drills attempted yet.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
