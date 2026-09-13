@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('trainer.practical.index') }}" class="btn btn-sm btn-outline-secondary mb-2"><i class="fas fa-arrow-left mr-1"></i> Back to Drills</a>
            <h2 class="h3 font-weight-bold text-dark mb-1">{{ $practical->title }}</h2>
            <p class="text-muted mb-0">Scenario Node Builder & Decision Trees</p>
        </div>
        <div>
            <span class="badge {{ $practical->status == 'published' ? 'badge-success' : 'badge-warning' }} p-2 mr-2">
                {{ strtoupper($practical->status) }}
            </span>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addNodeModal">
                <i class="fas fa-plus-circle mr-1"></i> Add Scenario Step Node
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <!-- Node Tree Visual / List -->
        <div class="col-lg-12">
            @forelse($practical->nodes as $node)
                <div class="card shadow-sm border-0 mb-4 {{ $node->is_start ? 'border-left border-primary' : '' }}" style="{{ $node->is_start ? 'border-left-width: 5px !important;' : '' }}">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge badge-dark mr-2">Node #{{ $node->id }}</span>
                            <strong class="h5 mb-0 text-dark">{{ $node->title }}</strong>
                            @if($node->is_start)
                                <span class="badge badge-primary ml-2"><i class="fas fa-flag-checkered mr-1"></i> STARTING SITUATION</span>
                            @endif
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-success mr-1" data-toggle="modal" data-target="#addOptionModal{{ $node->id }}">
                                <i class="fas fa-plus"></i> Add Action Choice
                            </button>
                            @if(!$node->is_start)
                                <form action="{{ route('trainer.practical.nodes.destroy', $node) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this step node and its options?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="font-weight-bold text-muted text-uppercase small">Situation / Event Description:</h6>
                            <p class="card-text bg-white p-3 border rounded text-dark">{{ $node->situation_text }}</p>
                        </div>

                        <h6 class="font-weight-bold text-muted text-uppercase small mb-2">Available Decisions / User Actions ({{ $node->options->count() }}):</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Action Text</th>
                                        <th>Next Situation Node</th>
                                        <th>Score Impact</th>
                                        <th>Consequence & Feedback</th>
                                        <th class="text-center">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($node->options as $option)
                                        <tr>
                                            <td class="font-weight-bold">{{ $option->option_text }}</td>
                                            <td>
                                                @if($option->nextNode)
                                                    <span class="badge badge-info"><i class="fas fa-arrow-right mr-1"></i> Node #{{ $option->nextNode->id }}: {{ $option->nextNode->title }}</span>
                                                @else
                                                    <span class="badge badge-secondary"><i class="fas fa-stop-circle mr-1"></i> Concludes Simulation</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $option->score_delta >= 0 ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $option->score_delta >= 0 ? '+'.$option->score_delta : $option->score_delta }} pts
                                                </span>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    @if($option->consequence_text)
                                                        <strong>Consequence:</strong> {{ $option->consequence_text }}<br>
                                                    @endif
                                                    @if($option->feedback)
                                                        <span class="text-muted"><strong>Feedback:</strong> {{ $option->feedback }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('trainer.practical.options.destroy', $option) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove action?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-xs btn-outline-danger"><i class="fas fa-times"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-2 small">
                                                No action options added for this node yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Add Option Modal for Node -->
                <div class="modal fade" id="addOptionModal{{ $node->id }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <form action="{{ route('trainer.practical.options.store', $node) }}" method="POST">
                                @csrf
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title font-weight-bold">Add Action Option for "{{ $node->title }}"</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Action / Decision Choice Text <span class="text-danger">*</span></label>
                                        <input type="text" name="option_text" class="form-control" placeholder="e.g. Issue Immediate Evacuation Order for Zone A" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Next Situation Node</label>
                                        <select name="next_node_id" class="form-control">
                                            <option value="">-- Conclude Simulation Here (End Node) --</option>
                                            @foreach($practical->nodes as $targetNode)
                                                @if($targetNode->id !== $node->id)
                                                    <option value="{{ $targetNode->id }}">Node #{{ $targetNode->id }}: {{ $targetNode->title }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Select where the user will be routed after choosing this action.</small>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="font-weight-bold">Score Delta (Points)</label>
                                            <input type="number" name="score_delta" class="form-control" value="10" required>
                                            <small class="form-text text-muted">Positive score for good choices, negative for mistakes.</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Consequence Description</label>
                                        <textarea name="consequence_text" class="form-control" rows="2" placeholder="e.g. Evacuation bus routes were clear, saving 400 residents."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Educational Feedback</label>
                                        <textarea name="feedback" class="form-control" rows="2" placeholder="e.g. Standard protocol recommends evacuating low-lying coastal zones 6 hours before landfall."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Save Action Option</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info py-4 text-center">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <p class="mb-0">No scenario nodes added yet. Click <strong>"Add Scenario Step Node"</strong> above.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Add Node Modal -->
<div class="modal fade" id="addNodeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('trainer.practical.nodes.store', $practical) }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">Add Scenario Step Node</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Step Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Water Level Exceeds Danger Mark" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Situation Description <span class="text-danger">*</span></label>
                        <textarea name="situation_text" class="form-control" rows="4" placeholder="Detailed description of the emerging event..." required></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_start" value="1" class="form-check-input" id="isStartCheck">
                        <label class="form-check-label font-weight-bold" for="isStartCheck">Set as Starting Node for this simulation</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Scenario Node</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
