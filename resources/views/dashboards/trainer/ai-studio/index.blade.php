@extends('layouts.trainer')

@section('title', 'AI Content Generator')

@section('trainer_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold"><i class="bi bi-cpu text-primary me-2"></i> AI Content Generator</h2>
        <p class="text-muted mb-0">Upload a document or paste content. The AI will generate a full course draft — then you review, edit, and publish.</p>
    </div>
</div>

<div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3">
    <i class="bi bi-info-circle-fill fs-4 text-info flex-shrink-0"></i>
    <div><strong>GENERATE → REVIEW → EDIT → APPROVE → PUBLISH.</strong> AI-generated content is <em>never</em> published automatically. You must review and approve every draft before it becomes live.</div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4"><i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}</div>
@endif

<div class="row g-4 mb-5">
    {{-- Input Panel --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-magic me-2 text-primary"></i> Generate New Draft</h5>
            </div>
            <div class="card-body p-4">
                {{-- Tabs --}}
                <ul class="nav nav-pills mb-4 gap-2" id="inputTabs">
                    <li class="nav-item">
                        <button class="nav-link active rounded-pill px-4" id="tab-upload" onclick="switchTab('upload')">
                            <i class="bi bi-upload me-1"></i> Upload File
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-pill px-4" id="tab-paste" onclick="switchTab('paste')">
                            <i class="bi bi-clipboard-text me-1"></i> Paste Content
                        </button>
                    </li>
                </ul>

                <form action="{{ route('trainer.ai-studio.generate') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Upload Tab --}}
                    <div id="panel-upload">
                        <div class="border border-2 border-dashed border-primary rounded-4 p-4 mb-4 bg-light text-center" style="position:relative;">
                            <input type="file" name="document" id="document" class="position-absolute w-100 h-100 top-0 start-0 opacity-0" style="cursor:pointer;"
                                   onchange="updateFileSelection(this);">
                            <i class="bi bi-cloud-arrow-up text-primary" style="font-size:3rem;"></i>
                            <h6 class="fw-bold text-dark mt-2">Drop PDF, DOCX, or TXT here</h6>
                            <p class="text-muted small mb-0">Supports PDF, DOCX, TXT (Max 50MB)</p>
                            <div id="fileName" class="fw-bold text-primary mt-2 small"></div>
                        </div>
                    </div>

                    {{-- Paste Tab --}}
                    <div id="panel-paste" class="d-none">
                        <textarea name="pasted_content" class="form-control mb-4" rows="10" placeholder="Paste your syllabus, training material, or topic notes here..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Course Difficulty Level</label>
                        <select name="difficulty" class="form-select">
                            <option value="easy">Easy</option>
                            <option value="medium" selected>Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg rounded-pill w-100 fw-bold" id="generateBtn"
                            onclick="this.innerHTML='<span class=\'spinner-border spinner-border-sm me-2\'></span> Analyzing...';">
                        <i class="bi bi-magic me-2"></i> Generate Course Draft
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Drafts List --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-collection me-2 text-secondary"></i> Drafts Pipeline</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-4">Draft Title / Source</th>
                                <th class="py-3 text-center">Difficulty</th>
                                <th class="py-3 text-center">Status</th>
                                <th class="py-3 text-end px-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($drafts as $draft)
                            <tr>
                                <td class="px-4">
                                    <div class="fw-bold">{{ $draft->draft_title ?: Str::limit($draft->source_file_name ?: 'Pasted Content', 30) }}</div>
                                    <div class="text-muted small">{{ $draft->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill text-capitalize">{{ $draft->draft_difficulty }}</span>
                                </td>
                                <td class="text-center">
                                    @if($draft->status === 'drafted')
                                        <span class="badge bg-warning text-dark rounded-pill">Needs Review</span>
                                    @elseif($draft->status === 'approved')
                                        <span class="badge bg-success rounded-pill">Approved</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill">Rejected</span>
                                    @endif
                                </td>
                                <td class="px-4 text-end">
                                    <a href="{{ route('trainer.ai-studio.draft', $draft->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                        {{ $draft->status === 'drafted' ? 'Review & Edit' : 'View' }}
                                        <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder2-open fs-1 d-block mb-3"></i>
                                    No drafts yet. Generate your first one!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    document.getElementById('panel-upload').classList.toggle('d-none', tab !== 'upload');
    document.getElementById('panel-paste').classList.toggle('d-none', tab !== 'paste');
    document.getElementById('tab-upload').classList.toggle('active', tab === 'upload');
    document.getElementById('tab-paste').classList.toggle('active', tab === 'paste');
}

function updateFileSelection(input) {
    const file = input.files[0];
    const display = document.getElementById('fileName');
    if (file) {
        let sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        display.innerHTML = `<span class="badge bg-primary text-white p-2 px-3 rounded-pill mt-2"><i class="bi bi-file-earmark-check-fill me-1"></i> ${file.name} (${sizeMB} MB)</span>`;
    } else {
        display.innerHTML = '';
    }
}
</script>
@endsection
