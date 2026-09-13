@extends('layouts.admin')

@section('title', 'Homepage Content Management')

@section('admin_content')
<div class="container-fluid py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold"><i class="bi bi-layout-text-window-reverse text-primary me-2"></i> Homepage Content Management</h2>
            <p class="text-muted mb-0">Customize hero copy, notices, section visibility, and public portal highlights.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.homepage-settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h5 class="fw-bold text-primary mb-3">Hero Banner Configuration</h5>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hero Title <span class="text-danger">*</span></label>
                            <input type="text" name="hero_title" class="form-control rounded-3" value="{{ old('hero_title', $setting->hero_title) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Hero Subtitle / Description <span class="text-danger">*</span></label>
                            <textarea name="hero_description" class="form-control rounded-3" rows="3" required>{{ old('hero_description', $setting->hero_description) }}</textarea>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold text-primary mb-3">Executive Notice & Announcements</h5>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Important Notice (Top Banner)</label>
                            <textarea name="important_notice" class="form-control rounded-3" rows="2" placeholder="e.g. Mandatory Disaster Preparedness Certification Drill scheduled for next week.">{{ old('important_notice', $setting->important_notice) }}</textarea>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold text-primary mb-3">Homepage Section Toggles</h5>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="show_ai_section" id="aiCheck" value="1" {{ $setting->show_ai_section ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="aiCheck">Enable AI-Powered Capabilities Section</label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="show_disaster_section" id="disasterCheck" value="1" {{ $setting->show_disaster_section ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="disasterCheck">Enable Disaster Response & Practical Drills Section</label>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="show_offline_section" id="offlineCheck" value="1" {{ $setting->show_offline_section ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="offlineCheck">Enable Offline Learning & PWA Capability Section</label>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Save Homepage Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
