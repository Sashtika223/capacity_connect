@extends('layouts.trainer')

@section('trainer_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Manage Assessment: {{ $assessment->title }}</h2>
    <a href="{{ route('trainer.assessments.index') }}" class="btn btn-outline-secondary">Back to Assessments</a>
</div>

<div class="row">
    <div class="col-lg-7">
        <!-- Edit Assessment Settings Form -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Settings</h5>
                <form method="POST" action="{{ route('trainer.assessments.update', $assessment->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" value="{{ $assessment->title }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="draft" {{ $assessment->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $assessment->status == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control" name="subject" value="{{ $assessment->subject }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Duration (min)</label>
                            <input type="number" class="form-control" name="duration" value="{{ $assessment->duration }}" required min="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Passing Score (%)</label>
                            <input type="number" class="form-control" name="passing_score" value="{{ $assessment->passing_score }}" required min="1" max="100">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="datetime-local" class="form-control" name="start_date" value="{{ $assessment->start_date ? $assessment->start_date->format('Y-m-d\TH:i') : '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="datetime-local" class="form-control" name="end_date" value="{{ $assessment->end_date ? $assessment->end_date->format('Y-m-d\TH:i') : '' }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Instructions</label>
                            <textarea class="form-control" name="instructions" rows="3">{{ $assessment->instructions }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">Update Settings</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Questions List -->
        <h4 class="fw-bold mt-5 mb-3">Questions ({{ $assessment->questions->count() }})</h4>
        @forelse($assessment->questions as $index => $question)
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h6 class="fw-bold mb-0">Q{{ $index + 1 }}. {{ $question->question_text }}</h6>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-light text-dark border">{{ $question->marks }} Marks</span>
                            <form action="{{ route('trainer.questions.destroy', $question->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete question?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    
                    <ul class="list-group list-group-flush">
                        @foreach($question->options as $option)
                            <li class="list-group-item px-3 py-2 {{ $option->is_correct ? 'bg-success bg-opacity-10 text-success fw-bold' : 'text-muted' }}">
                                @if($option->is_correct)
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                @else
                                    <i class="bi bi-circle me-2"></i>
                                @endif
                                {{ $option->option_text }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No questions added yet. Use the form to add questions.</div>
        @endforelse
    </div>

    <!-- Right Sidebar for Adding Questions -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Add New Question (MCQ)</h5>
                <form method="POST" action="{{ route('trainer.questions.store', $assessment->id) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Question Text</label>
                        <textarea class="form-control" name="question_text" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Marks</label>
                        <input type="number" class="form-control" name="marks" value="1" min="1" required>
                    </div>

                    <label class="form-label">Options</label>
                    <div class="bg-light p-3 rounded-3 mb-3">
                        @for($i = 0; $i < 4; $i++)
                            <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="correct_option" value="{{ $i }}" {{ $i == 0 ? 'required checked' : '' }}>
                                </div>
                                <input type="text" class="form-control" name="options[]" placeholder="Option {{ $i + 1 }}" {{ $i < 2 ? 'required' : '' }}>
                            </div>
                        @endfor
                        <div class="form-text mt-2"><i class="bi bi-info-circle"></i> Select the radio button next to the correct option. Minimum 2 options required.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Add Question</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
