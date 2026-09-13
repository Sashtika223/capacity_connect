@extends('layouts.trainer')

@section('title', 'Review & Edit AI Draft')

@section('trainer_content')
<div class="mb-4">
    <a href="{{ route('trainer.ai-studio.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
        <i class="bi bi-arrow-left me-1"></i> Back to Studio
    </a>
    <h2 class="fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i> Review & Edit Draft</h2>
    <p class="text-muted mb-0">Edit any field below. Click <strong>Approve & Publish</strong> only when you are satisfied with the content.</p>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="alert alert-warning border-warning border-start border-5 rounded-4 mb-4 d-flex gap-3 align-items-start">
    <i class="bi bi-exclamation-triangle-fill text-warning fs-4 mt-1 flex-shrink-0"></i>
    <div>All content below was AI-generated from: <strong>{{ $draft->source_file_name ?: 'Pasted Content' }}</strong>.
    Review carefully before approving. AI-generated content is <em>never</em> auto-published.</div>
</div>

<form action="{{ route('trainer.ai-studio.approve', $draft->id) }}" method="POST" id="approveForm">
@csrf

{{-- Course Info --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">📘 Course Information</h5>
        <span class="badge bg-{{ $draft->draft_difficulty === 'hard' ? 'danger' : ($draft->draft_difficulty === 'medium' ? 'warning text-dark' : 'success') }} rounded-pill text-capitalize px-3">
            {{ ucfirst($draft->draft_difficulty) }} Difficulty
        </span>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Course Title</label>
                <input type="text" name="draft_title" class="form-control" value="{{ $draft->draft_title }}" placeholder="Generated course title..." {{ $draft->status !== 'drafted' ? 'disabled' : '' }}>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Difficulty Level</label>
                <select name="draft_difficulty" class="form-select" {{ $draft->status !== 'drafted' ? 'disabled' : '' }}>
                    <option value="easy" {{ $draft->draft_difficulty === 'easy' ? 'selected' : '' }}>Easy</option>
                    <option value="medium" {{ $draft->draft_difficulty === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="hard" {{ $draft->draft_difficulty === 'hard' ? 'selected' : '' }}>Hard</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Course Description</label>
                <textarea name="draft_description" class="form-control" rows="3" placeholder="Generated course description..." {{ $draft->status !== 'drafted' ? 'disabled' : '' }}>{{ $draft->draft_description }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Summary --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom p-3"><h6 class="fw-bold mb-0">📝 Summary</h6></div>
            <div class="card-body p-3">
                <textarea name="draft_summary" class="form-control" rows="5" placeholder="AI-generated summary..." {{ $draft->status !== 'drafted' ? 'disabled' : '' }}>{{ $draft->draft_summary }}</textarea>
            </div>
        </div>
    </div>

    {{-- Learning Objectives --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom p-3"><h6 class="fw-bold mb-0">🎯 Learning Objectives</h6></div>
            <div class="card-body p-3">
                <div id="objectives-list">
                    @if(is_array($draft->draft_objectives))
                        @foreach($draft->draft_objectives as $idx => $obj)
                        <div class="d-flex gap-2 mb-2 objective-item">
                            <input type="text" name="draft_objectives[]" class="form-control form-control-sm" value="{{ $obj }}" {{ $draft->status !== 'drafted' ? 'disabled' : '' }}>
                            @if($draft->status === 'drafted')
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
                            @endif
                        </div>
                        @endforeach
                    @endif
                </div>
                @if($draft->status === 'drafted')
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill mt-2" onclick="addObjective()"><i class="bi bi-plus me-1"></i> Add Objective</button>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Outline --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom p-3"><h6 class="fw-bold mb-0">📚 Course Outline / Modules</h6></div>
            <div class="card-body p-3">
                <div id="outline-list">
                    @if(is_array($draft->draft_outline))
                        @foreach($draft->draft_outline as $module)
                        <div class="d-flex gap-2 mb-2">
                            <input type="text" name="draft_outline[]" class="form-control form-control-sm" value="{{ $module }}" {{ $draft->status !== 'drafted' ? 'disabled' : '' }}>
                            @if($draft->status === 'drafted')
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
                            @endif
                        </div>
                        @endforeach
                    @endif
                </div>
                @if($draft->status === 'drafted')
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill mt-2" onclick="addOutline()"><i class="bi bi-plus me-1"></i> Add Module</button>
                @endif
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom p-3"><h6 class="fw-bold mb-0">🗒️ Notes / Key Points</h6></div>
            <div class="card-body p-3">
                <div id="notes-list">
                    @if(is_array($draft->draft_notes))
                        @foreach($draft->draft_notes as $note)
                        <div class="d-flex gap-2 mb-2">
                            <input type="text" name="draft_notes[]" class="form-control form-control-sm" value="{{ $note }}" {{ $draft->status !== 'drafted' ? 'disabled' : '' }}>
                            @if($draft->status === 'drafted')
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
                            @endif
                        </div>
                        @endforeach
                    @endif
                </div>
                @if($draft->status === 'drafted')
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill mt-2" onclick="addNote()"><i class="bi bi-plus me-1"></i> Add Note</button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MCQs --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom p-4">
        <h5 class="fw-bold mb-0">❓ Multiple Choice Questions (MCQs) & Assessment</h5>
    </div>
    <div class="card-body p-4">
        @if(is_array($draft->draft_mcqs) && count($draft->draft_mcqs) > 0)
            @foreach($draft->draft_mcqs as $qi => $mcq)
            <div class="card border rounded-4 mb-3 bg-light">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary rounded-pill">Q{{ $qi + 1 }}</span>
                        <span class="badge bg-{{ $mcq['difficulty'] === 'hard' ? 'danger' : ($mcq['difficulty'] === 'medium' ? 'warning text-dark' : 'success') }} rounded-pill text-capitalize">
                            {{ ucfirst($mcq['difficulty'] ?? 'medium') }}
                        </span>
                    </div>
                    <div class="fw-bold mb-2">{{ $mcq['question'] }}</div>
                    <ul class="list-unstyled mb-2 ms-2 small">
                        @foreach($mcq['options'] as $opt)
                            <li class="mb-1 {{ $opt === $mcq['correct_answer'] ? 'text-success fw-bold' : 'text-muted' }}">
                                <i class="bi bi-{{ $opt === $mcq['correct_answer'] ? 'check-circle-fill text-success' : 'circle' }} me-1"></i> {{ $opt }}
                            </li>
                        @endforeach
                    </ul>
                    @if(isset($mcq['explanation']))
                        <div class="text-muted small fst-italic"><i class="bi bi-lightbulb me-1 text-warning"></i> <strong>Explanation:</strong> {{ $mcq['explanation'] }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        @else
            <p class="text-muted">No MCQs were generated. This may be because the AI is not yet configured.</p>
        @endif
    </div>
</div>

{{-- Action Buttons --}}
@if($draft->status === 'drafted')
<div class="d-flex gap-3 justify-content-end pb-4">
    <button type="submit" name="action" value="save" class="btn btn-outline-primary rounded-pill px-4">
        <i class="bi bi-floppy me-2"></i> Save Edits
    </button>
    <button type="submit" name="action" value="approve" class="btn btn-success btn-lg rounded-pill px-5 fw-bold"
            onclick="return confirm('Approve this draft and publish it to the course catalog?')">
        <i class="bi bi-check2-all me-2"></i> Approve & Publish to Course
    </button>
</div>
@else
    <div class="alert alert-success rounded-4 border-0 shadow-sm d-flex align-items-center gap-3 mb-4">
        <i class="bi bi-check-circle-fill text-success fs-4"></i>
        <span>This draft has been <strong>approved</strong> and published to the course catalog.</span>
    </div>
@endif

</form>

<script>
function addObjective() {
    const list = document.getElementById('objectives-list');
    const div = document.createElement('div');
    div.className = 'd-flex gap-2 mb-2 objective-item';
    div.innerHTML = `<input type="text" name="draft_objectives[]" class="form-control form-control-sm" placeholder="New learning objective...">
                     <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>`;
    list.appendChild(div);
}
function addOutline() {
    const list = document.getElementById('outline-list');
    const div = document.createElement('div');
    div.className = 'd-flex gap-2 mb-2';
    div.innerHTML = `<input type="text" name="draft_outline[]" class="form-control form-control-sm" placeholder="New module...">
                     <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>`;
    list.appendChild(div);
}
function addNote() {
    const list = document.getElementById('notes-list');
    const div = document.createElement('div');
    div.className = 'd-flex gap-2 mb-2';
    div.innerHTML = `<input type="text" name="draft_notes[]" class="form-control form-control-sm" placeholder="New note...">
                     <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>`;
    list.appendChild(div);
}
</script>
@endsection
