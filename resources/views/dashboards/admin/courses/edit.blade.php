@extends('layouts.admin')

@section('title', 'Admin - Edit Course')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Edit Course: <span class="text-primary">{{ $course->title }}</span></h2>
    <a href="{{ route('admin.courses.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Back to Courses
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('admin.courses.update', $course->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Course Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control rounded-3 @error('title') is-invalid @enderror" value="{{ old('title', $course->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-4">
                    <label class="form-label fw-bold">Course Code <span class="text-danger">*</span></label>
                    <input type="text" name="course_code" class="form-control rounded-3 @error('course_code') is-invalid @enderror" value="{{ old('course_code', $course->course_code) }}" required>
                    @error('course_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select rounded-3 @error('category_id') is-invalid @enderror" required>
                        <option value="">Select Category...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Assign Trainer <span class="text-danger">*</span></label>
                    <select name="trainer_id" class="form-select rounded-3 @error('trainer_id') is-invalid @enderror" required>
                        <option value="">Select Trainer...</option>
                        @foreach($trainers as $trainer)
                            <option value="{{ $trainer->id }}" {{ old('trainer_id', $course->trainer_id) == $trainer->id ? 'selected' : '' }}>
                                {{ $trainer->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('trainer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Difficulty <span class="text-danger">*</span></label>
                    <select name="difficulty" class="form-select rounded-3 @error('difficulty') is-invalid @enderror" required>
                        <option value="beginner" {{ old('difficulty', $course->difficulty) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('difficulty', $course->difficulty) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('difficulty', $course->difficulty) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                    @error('difficulty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Publish Status <span class="text-danger">*</span></label>
                    <select name="publish_status" class="form-select rounded-3 @error('publish_status') is-invalid @enderror" required>
                        <option value="draft" {{ old('publish_status', $course->publish_status) == 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                        <option value="published" {{ old('publish_status', $course->publish_status) == 'published' ? 'selected' : '' }}>Published (Live)</option>
                    </select>
                    @error('publish_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Course Description <span class="text-danger">*</span></label>
                    <textarea name="description" rows="5" class="form-control rounded-3 @error('description') is-invalid @enderror" required>{{ old('description', $course->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr class="my-5">

            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('admin.courses.index') }}" class="btn btn-light rounded-pill px-4">Cancel</a>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
