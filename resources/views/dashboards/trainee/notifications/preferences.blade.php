@extends('layouts.trainee')

@section('title', 'Notification Preferences')

@section('trainee_content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold"><i class="bi bi-bell-fill me-2"></i> Notification Preferences</h5>
                    <a href="{{ route('trainee.notifications') }}" class="btn btn-sm btn-light">Back to Notifications</a>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('trainee.notifications.preferences.update') }}" method="POST">
                        @csrf

                        <h6 class="fw-bold text-primary mb-3">Delivery Channels</h6>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="in_app_notifications" id="inAppCheck" value="1" {{ $pref->in_app_notifications ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="inAppCheck">In-App Notifications</label>
                            <div class="form-text">Receive instant popovers and top bar indicators when active on portal.</div>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="email_notifications" id="emailCheck" value="1" {{ $pref->email_notifications ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="emailCheck">Email Alerts</label>
                            <div class="form-text">Receive summary digests and high-priority messages in your email inbox.</div>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold text-primary mb-3">Alert Topics & Triggers</h6>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="new_course_alerts" id="newCourseCheck" value="1" {{ $pref->new_course_alerts ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="newCourseCheck">New Course Releases & Assignments</label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="assessment_alerts" id="assessmentCheck" value="1" {{ $pref->assessment_alerts ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="assessmentCheck">Upcoming Assessments & Result Publications</label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="certificate_alerts" id="certCheck" value="1" {{ $pref->certificate_alerts ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="certCheck">Certificate Issuances & Expiry Reminders</label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="announcement_alerts" id="announceCheck" value="1" {{ $pref->announcement_alerts ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="announceCheck">System Announcements & Executive Directives</label>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="skill_gap_alerts" id="skillCheck" value="1" {{ $pref->skill_gap_alerts ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="skillCheck">Skill Gap Recommendations & Digital Twin Alerts</label>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Save Preferences</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
