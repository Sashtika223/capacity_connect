@extends('layouts.trainer')

@section('trainer_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Create New Course</h2>
    <a href="{{ route('trainer.courses.index') }}" class="btn btn-outline-secondary">Back to Courses</a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-md-5">
        <form method="POST" action="{{ route('trainer.courses.store') }}">
            @csrf
            
            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <label class="form-label">Course Title</label>
                    <input type="text" class="form-control" name="title" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Course Code</label>
                    <input type="text" class="form-control" name="course_code" required placeholder="e.g. CS101">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Category</label>
                    <select class="form-select" name="category_id" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Difficulty</label>
                    <select class="form-select" name="difficulty" required>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Duration (Optional)</label>
                    <input type="text" class="form-control" name="duration" placeholder="e.g. 4 weeks">
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="4" required></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">Create Course</button>
            </div>
        </form>
    </div>
</div>
@endsection
