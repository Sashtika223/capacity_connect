@extends('layouts.trainee')

@section('trainee_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">My Profile</h2>
</div>

@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-md-5">
        <form method="POST" action="{{ route('trainee.profile.update') }}">
            @csrf
            
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
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-control" name="phone" value="{{ old('phone', $profile->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Department</label>
                    <input type="text" class="form-control" name="department" value="{{ old('department', $profile->department) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Designation / Role</label>
                    <input type="text" class="form-control" name="designation" value="{{ old('designation', $profile->designation) }}">
                </div>
            </div>

            <hr class="my-5">
            <h5 class="fw-bold mb-4 text-primary">Professional Details</h5>

            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label">Qualifications</label>
                    <textarea class="form-control" name="qualifications" rows="3">{{ old('qualifications', $profile->qualifications) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Work Experience</label>
                    <textarea class="form-control" name="work_experience" rows="3">{{ old('work_experience', $profile->work_experience) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Skills</label>
                    <textarea class="form-control" name="skills" rows="2" placeholder="e.g. PHP, Laravel, Project Management">{{ old('skills', $profile->skills) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Interests</label>
                    <textarea class="form-control" name="interests" rows="2">{{ old('interests', $profile->interests) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">External Certificates (URLs or Details)</label>
                    <textarea class="form-control" name="certificates_list" rows="2">{{ old('certificates_list', $profile->certificates_list) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">Save Profile</button>
            </div>
        </form>
    </div>
</div>
@endsection
