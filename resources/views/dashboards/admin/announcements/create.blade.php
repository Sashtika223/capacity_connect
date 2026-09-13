@extends('layouts.admin')

@section('title', 'Admin - Create Announcement')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Create Announcement</h2>
    <a href="{{ route('admin.announcements.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Back
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('admin.announcements.store') }}" method="POST">
            @csrf
            
            <div class="row g-4">
                <div class="col-md-12">
                    <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control rounded-3 @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-12">
                    <label class="form-label fw-bold">Content <span class="text-danger">*</span></label>
                    <textarea name="content" rows="6" class="form-control rounded-3 @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
                    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Publish Immediately?</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                        <label class="form-check-label text-muted" for="is_published">Yes, make it live</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Schedule (Optional)</label>
                    <input type="datetime-local" name="published_at" class="form-control rounded-3 @error('published_at') is-invalid @enderror" value="{{ old('published_at') }}">
                    <div class="form-text">Leave blank to use current time if published.</div>
                    @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Expiry Date (Optional)</label>
                    <input type="datetime-local" name="expires_at" class="form-control rounded-3 @error('expires_at') is-invalid @enderror" value="{{ old('expires_at') }}">
                    <div class="form-text">When should this be hidden?</div>
                    @error('expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr class="my-5">

            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-light rounded-pill px-4">Cancel</a>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Create Announcement</button>
            </div>
        </form>
    </div>
</div>
@endsection
