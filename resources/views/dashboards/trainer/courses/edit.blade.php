@extends('layouts.trainer')

@section('trainer_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Manage Course: {{ $course->title }}</h2>
    <a href="{{ route('trainer.courses.index') }}" class="btn btn-outline-secondary">Back to Courses</a>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Edit Course Form -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Course Details</h5>
                <form method="POST" action="{{ route('trainer.courses.update', $course->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Course Title</label>
                            <input type="text" class="form-control" name="title" value="{{ $course->title }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="publish_status" required>
                                <option value="draft" {{ $course->publish_status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $course->publish_status == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $course->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="4" required>{{ $course->description }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">Update Details</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modules List -->
        <h4 class="fw-bold mt-5 mb-3">Curriculum</h4>
        @forelse($course->modules as $module)
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-primary mb-0">{{ $module->title }}</h5>
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#collapseModule{{ $module->id }}">Show Lessons</button>
                    </div>
                    
                    <div class="collapse" id="collapseModule{{ $module->id }}">
                        <ul class="list-group list-group-flush mb-3">
                            @forelse($module->lessons as $lesson)
                                <li class="list-group-item px-0 py-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span><i class="bi bi-play-circle text-muted me-2"></i> <strong>{{ $lesson->title }}</strong></span>
                                        <span class="badge bg-light text-dark">{{ $lesson->duration }} min</span>
                                    </div>
                                    
                                    <!-- Lesson Resources -->
                                    <div class="ps-4 mb-2">
                                        @foreach($lesson->resources as $resource)
                                            <div class="d-flex justify-content-between small text-muted mb-1 bg-light p-2 rounded">
                                                <span><i class="bi bi-paperclip me-1"></i> <a href="{{ $resource->file_path }}" target="_blank" class="text-decoration-none">{{ $resource->title }}</a> ({{ strtoupper($resource->type) }})</span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Upload Resource Form -->
                                    <div class="ps-4 mt-2 border-start border-2 border-primary ms-2 py-2">
                                        <form method="POST" action="{{ route('trainer.resources.store', $lesson->id) }}" enctype="multipart/form-data" class="row g-2 align-items-center">
                                            @csrf
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" name="title" placeholder="Resource Name" required>
                                            </div>
                                            <div class="col-sm-3">
                                                <select class="form-select form-select-sm" name="type" required>
                                                    <option value="document">Presentation / Doc</option>
                                                    <option value="pdf">PDF</option>
                                                    <option value="other">Study Material (Other)</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="file" class="form-control form-control-sm" name="file" required>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="submit" class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-upload"></i> Upload</button>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item px-0 text-muted">No lessons added to this module yet.</li>
                            @endforelse
                        </ul>

                        <!-- Add Lesson Form -->
                        <div class="bg-light p-3 rounded-3 mt-3">
                            <h6 class="fw-bold mb-3 small text-muted text-uppercase">Add New Lesson</h6>
                            <form method="POST" action="{{ route('trainer.lessons.store', $module->id) }}">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-sm" name="title" placeholder="Lesson Title" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control form-control-sm" name="video_url" placeholder="Video URL (Optional)">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-sm btn-primary w-100">Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No modules added yet. Use the form to the right to create your first module.</div>
        @endforelse
    </div>

    <!-- Right Sidebar for Adding Modules -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Add New Module</h5>
                <form method="POST" action="{{ route('trainer.modules.store', $course->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Module Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Module</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
