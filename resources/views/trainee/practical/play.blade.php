@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header info banner -->
    <div class="card shadow-sm border-0 bg-dark text-white mb-4">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="badge badge-danger p-2 mb-2"><i class="fas fa-exclamation-triangle mr-1"></i> DISASTER RESPONSE DRILL</span>
                <h2 class="h4 font-weight-bold mb-1">{{ $attempt->assessment->title }}</h2>
                <p class="text-white-50 mb-0">Evaluate circumstances and choose the optimal operational response.</p>
            </div>
            <div class="mt-3 mt-md-0 text-md-right">
                <div class="small text-white-50 text-uppercase font-weight-bold">Current Decision Score</div>
                <div class="display-4 font-weight-bold text-warning">{{ $attempt->final_score ?? 0 }} <span class="h5">pts</span></div>
            </div>
        </div>
    </div>

    @if($lastLog && $lastLog->consequence_text && $lastLog->option_id)
        <div class="alert alert-info shadow-sm mb-4 border-left border-info" style="border-left-width: 5px !important;">
            <h6 class="font-weight-bold mb-1"><i class="fas fa-info-circle mr-1"></i> Consequence of Previous Decision:</h6>
            <p class="mb-0">{{ $lastLog->consequence_text }}</p>
        </div>
    @endif

    <div class="row">
        <!-- Current Situation Node -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-compass mr-2"></i> Current Situation: {{ $currentNode->title }}</h5>
                </div>
                <div class="card-body p-4 d-flex flex-column">
                    <div class="bg-light p-4 rounded mb-4 border border-secondary text-dark lead" style="line-height: 1.7;">
                        {{ $currentNode->situation_text }}
                    </div>

                    <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-route text-success mr-2"></i>Select Your Operational Action:</h5>

                    <form action="{{ route('trainee.practical.choose', $attempt) }}" method="POST">
                        @csrf
                        <div class="list-group mb-4">
                            @forelse($currentNode->options as $option)
                                <label class="list-group-item list-group-item-action p-3 mb-2 border rounded shadow-xs cursor-pointer d-flex align-items-center">
                                    <input type="radio" name="option_id" value="{{ $option->id }}" class="mr-3" required style="transform: scale(1.3);">
                                    <div class="flex-grow-1">
                                        <strong class="h6 text-dark mb-1 d-block">{{ $option->option_text }}</strong>
                                    </div>
                                </label>
                            @empty
                                <div class="alert alert-warning py-3">
                                    No further action options defined for this situation branch.
                                </div>
                            @endforelse
                        </div>

                        @if($currentNode->options->count() > 0)
                            <button type="submit" class="btn btn-success btn-lg btn-block shadow-sm font-weight-bold py-3">
                                Submit Decision & Proceed <i class="fas fa-chevron-right ml-2"></i>
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Simulation Progress & History Log -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-list-ol mr-2"></i>Decision Log Timeline</h6>
                </div>
                <div class="card-body p-3">
                    <div class="timeline">
                        @foreach($attempt->logs as $log)
                            <div class="p-2 mb-2 bg-white rounded border border-light shadow-xs">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="small text-primary">{{ $log->node->title ?? 'Situation Step' }}</strong>
                                    @if($log->score_delta != 0)
                                        <span class="badge {{ $log->score_delta >= 0 ? 'badge-success' : 'badge-danger' }}">
                                            {{ $log->score_delta >= 0 ? '+'.$log->score_delta : $log->score_delta }} pts
                                        </span>
                                    @endif
                                </div>
                                @if($log->option)
                                    <div class="small text-dark font-weight-bold">Action: {{ $log->option->option_text }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
