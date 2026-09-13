@extends('layouts.trainer')

@section('trainer_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">My Profile</h2>
        <p class="text-muted mb-0">Complete 100% of your profile to unlock Trainer Certification eligibility.</p>
    </div>
    <div>
        @if($profile->isComplete())
            <span class="badge bg-success-subtle text-success fs-6 px-3 py-2 border border-success-subtle rounded-pill">
                <i class="bi bi-shield-check me-1"></i> Eligible for Certification
            </span>
        @else
            <span class="badge bg-warning-subtle text-warning fs-6 px-3 py-2 border border-warning-subtle rounded-pill">
                <i class="bi bi-exclamation-triangle me-1"></i> Profile Incomplete ({{ $profile->completion_percentage }}%)
            </span>
        @endif
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Please correct the following issues:</h6>
        <ul class="mb-0 ps-3 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Profile Completion Progress Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-gradient">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-pie-chart-fill text-primary me-2"></i> Profile Completion Progress
            </h6>
            <span class="fw-bold fs-5 text-primary">{{ $profile->completion_percentage }}%</span>
        </div>
        <div class="progress rounded-pill mb-3" style="height: 12px;">
            <div class="progress-bar {{ $profile->completion_percentage == 100 ? 'bg-success' : 'bg-primary' }} progress-bar-striped progress-bar-animated" 
                 role="progressbar" 
                 style="width: {{ $profile->completion_percentage }}%;" 
                 aria-valuenow="{{ $profile->completion_percentage }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
            </div>
        </div>

        @if(!$profile->isComplete() && count($profile->missing_fields) > 0)
            <div class="alert alert-warning border-0 rounded-3 mb-0">
                <h6 class="fw-bold mb-2"><i class="bi bi-shield-exclamation me-1"></i> Required Fields to Reach 100% Completion:</h6>
                <ul class="mb-0 ps-3 small">
                    @foreach($profile->missing_fields as $missingField)
                        <li>{{ $missingField }}</li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="alert alert-success border-0 rounded-3 mb-0 d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-award-fill me-2"></i>
                    <strong>Congratulations! Your profile is 100% complete.</strong> You are eligible for course scenario certifications assigned by Administrators.
                </div>
                <a href="{{ route('trainer.certifications.index') }}" class="btn btn-sm btn-success rounded-pill px-3">View Certifications</a>
            </div>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-md-5">
        <form method="POST" action="{{ route('trainer.profile.update') }}" enctype="multipart/form-data">
            @csrf
            
            <input type="hidden" name="photo_base64" id="photoBase64">

            <div class="row mb-5 align-items-center">
                <div class="col-auto">
                    <div id="avatarContainer" class="rounded-circle overflow-hidden d-flex align-items-center justify-content-center bg-secondary text-white" style="width: 120px; height: 120px; border: 3px solid #0B2545;">
                        @if($profile->photo)
                            <img id="trainerPhotoPreview" src="{{ $profile->photo }}" alt="Profile Photo" class="w-100 h-100 object-fit-cover">
                        @else
                            <img id="trainerPhotoPreview" src="" alt="Profile Photo" class="w-100 h-100 object-fit-cover d-none">
                            <i id="trainerPhotoIcon" class="bi bi-person fs-1"></i>
                        @endif
                    </div>
                </div>
                <div class="col">
                    <label class="form-label fw-bold">Upload New Photo</label>
                    <input type="file" class="form-control w-auto" name="photo" id="trainerPhotoInput" accept="image/*">
                    <div class="form-text">All square & rectangular images (JPEG, PNG, WebP) are auto-optimized and supported.</div>
                    @error('photo')
                        <div class="text-danger small mt-1"><i class="bi bi-exclamation-triangle me-1"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const fileInput = document.getElementById('trainerPhotoInput');
                    const previewImg = document.getElementById('trainerPhotoPreview');
                    const iconPlaceholder = document.getElementById('trainerPhotoIcon');
                    const base64Input = document.getElementById('photoBase64');

                    if (!fileInput) return;

                    fileInput.addEventListener('change', function (e) {
                        const file = e.target.files[0];
                        if (!file) return;

                        const reader = new FileReader();
                        reader.onload = function (event) {
                            const img = new Image();
                            img.onload = function () {
                                const canvas = document.createElement('canvas');
                                const maxDim = 400;
                                let width = img.width;
                                let height = img.height;

                                if (width > height) {
                                    if (width > maxDim) {
                                        height = Math.round((height * maxDim) / width);
                                        width = maxDim;
                                    }
                                } else {
                                    if (height > maxDim) {
                                        width = Math.round((width * maxDim) / height);
                                        height = maxDim;
                                    }
                                }

                                canvas.width = width;
                                canvas.height = height;
                                const ctx = canvas.getContext('2d');
                                ctx.drawImage(img, 0, 0, width, height);

                                const compressedBase64 = canvas.toDataURL('image/jpeg', 0.85);
                                base64Input.value = compressedBase64;
                                previewImg.src = compressedBase64;
                                previewImg.classList.remove('d-none');
                                if (iconPlaceholder) iconPlaceholder.classList.add('d-none');
                            };
                            img.src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    });

                    const form = fileInput.closest('form');
                    if (form) {
                        form.addEventListener('submit', function () {
                            if (base64Input && base64Input.value) {
                                fileInput.removeAttribute('name');
                            }
                        });
                    }
                });
            </script>

            <h5 class="fw-bold mb-4 text-primary">Basic Information</h5>
            
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email Address (Read Only)</label>
                    <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Department</label>
                    <input type="text" class="form-control" name="department" value="{{ old('department', $profile->department) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Qualification</label>
                    <input type="text" class="form-control" name="qualification" value="{{ old('qualification', $profile->qualification) }}" placeholder="e.g. Ph.D. in Computer Science">
                </div>
            </div>

            <hr class="my-5">
            <h5 class="fw-bold mb-4 text-primary">Professional Details</h5>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Expertise / Domain</label>
                    <input type="text" class="form-control" name="expertise" value="{{ old('expertise', $profile->expertise) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Subjects Handled</label>
                    <input type="text" class="form-control" name="subjects" value="{{ old('subjects', $profile->subjects) }}" placeholder="Comma separated">
                </div>
                <div class="col-12">
                    <label class="form-label">Professional Experience</label>
                    <textarea class="form-control" name="experience" rows="3">{{ old('experience', $profile->experience) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Short Bio</label>
                    <textarea class="form-control" name="bio" rows="4" placeholder="Tell trainees a bit about yourself...">{{ old('bio', $profile->bio) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">Save Profile</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mt-5">
    <div class="card-body p-4 p-md-5">
        <h5 class="fw-bold mb-4 text-primary">My Competencies</h5>
        <p class="text-muted mb-4">The following competencies have been mapped to your profile by the Administration. These highlight your expertise to trainees across the platform.</p>
        
        @if($competencies->count() > 0)
            <div class="row g-3">
                @foreach($competencies as $comp)
                @php
                    $levelLabels = [0 => 'None', 1 => 'Beginner', 2 => 'Intermediate', 3 => 'Advanced', 4 => 'Expert'];
                    $level = $comp->pivot->current_level;
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card border border-light-subtle shadow-none h-100 rounded-3">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2">{{ $comp->name }}</h6>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3">{{ $levelLabels[$level] }}</span>
                            @if($comp->type)
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 ms-1 text-capitalize">{{ $comp->type }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-light border border-light-subtle text-center py-4 rounded-3 text-muted">
                <i class="bi bi-info-circle fs-2 d-block mb-2"></i>
                No competencies have been mapped to your profile yet.
            </div>
        @endif
    </div>
</div>
@endsection
