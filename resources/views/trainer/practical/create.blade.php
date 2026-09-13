@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>Create Practical Response Drill</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('trainer.practical.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Drill Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Cyclone Response & Relief Management Drill" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Description / Learning Scenario Overview</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Briefly describe the scenario background, weather forecast, or disaster context...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Associated Course (Optional)</label>
                            <select name="course_id" class="form-control">
                                <option value="">-- Standalone Practical Assessment --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Status</label>
                            <select name="status" class="form-control">
                                <option value="draft">Draft (Work in progress)</option>
                                <option value="published">Published (Available to Trainees)</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('trainer.practical.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-arrow-right mr-1"></i> Proceed to Scenario Node Builder</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
