@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white py-4 text-center">
                    <i class="fas fa-trophy fa-3x mb-2"></i>
                    <h2 class="font-weight-bold mb-1">Practical Drill Completed!</h2>
                    <p class="mb-0 text-white-50">{{ $attempt->assessment->title }}</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="small text-uppercase font-weight-bold text-muted mb-1">Final Operational Score</div>
                        <div class="display-3 font-weight-bold text-success">{{ $attempt->final_score }} <span class="h4 text-muted">points</span></div>
                        <p class="text-muted mt-2">Drill Completed on {{ $attempt->end_time ? $attempt->end_time->format('M d, Y \a\t g:i A') : now()->format('M d, Y') }}</p>
                    </div>

                    <h5 class="font-weight-bold text-dark mb-3 border-bottom pb-2">
                        <i class="fas fa-tasks mr-2 text-primary"></i>Decision Log & Operational Timeline
                    </h5>

                    <div class="timeline mb-4">
                        @foreach($attempt->logs as $index => $log)
                            <div class="card mb-3 border-0 bg-light">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="font-weight-bold text-dark mb-0">Step {{ $index + 1 }}: {{ $log->node->title ?? 'Scenario Node' }}</h6>
                                        @if($log->score_delta != 0)
                                            <span class="badge {{ $log->score_delta >= 0 ? 'badge-success' : 'badge-danger' }} p-2">
                                                {{ $log->score_delta >= 0 ? '+'.$log->score_delta : $log->score_delta }} pts
                                            </span>
                                        @endif
                                    </div>
                                    <p class="small text-muted mb-2">{{ $log->node->situation_text ?? '' }}</p>

                                    @if($log->option)
                                        <div class="bg-white p-3 rounded border">
                                            <div class="font-weight-bold text-primary mb-1">
                                                <i class="fas fa-check-circle mr-1"></i> Chosen Action: {{ $log->option->option_text }}
                                            </div>
                                            @if($log->consequence_text)
                                                <div class="small text-dark mb-1"><strong>Consequence:</strong> {{ $log->consequence_text }}</div>
                                            @endif
                                            @if($log->option->feedback)
                                                <div class="small text-secondary"><strong>Feedback:</strong> {{ $log->option->feedback }}</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('trainee.practical.index') }}" class="btn btn-outline-secondary px-4 font-weight-bold">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Practical Drills
                        </a>
                        <form action="{{ route('trainee.practical.start', $attempt->assessment) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary px-4 font-weight-bold">
                                <i class="fas fa-redo mr-1"></i> Re-Attempt Drill
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
