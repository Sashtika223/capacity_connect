@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-edit mr-2"></i>Edit Practical Response Drill</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('trainer.practical.update', $practical) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Drill Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $practical->title) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description', $practical->description) }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Associated Course</label>
                            <select name="course_id" class="form-control">
                                <option value="">-- Standalone Practical Assessment --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ $practical->course_id == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Status</label>
                            <select name="status" class="form-control">
                                <option value="draft" {{ $practical->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $practical->status == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('trainer.practical.show', $practical) }}" class="btn btn-light">Back to Node Builder</a>
                            <button type="submit" class="btn btn-success px-4">Update Drill Info</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
