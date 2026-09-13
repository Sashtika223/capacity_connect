@extends('layouts.trainee')

@section('trainee_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">My Certificates</h2>
</div>

<div class="row">
    @forelse($certificates as $certificate)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 border-0 shadow-sm rounded-4 text-center overflow-hidden">
            <div class="bg-primary bg-opacity-10 py-4">
                <i class="bi bi-patch-check-fill text-primary" style="font-size: 3rem;"></i>
            </div>
            <div class="card-body p-4">
                <h6 class="text-muted text-uppercase small fw-bold mb-1">Certificate of Completion</h6>
                <h5 class="fw-bold text-dark mb-3">{{ $certificate->course->title }}</h5>
                <p class="text-muted small mb-1">Issued: {{ $certificate->issue_date->format('F d, Y') }}</p>
                <p class="text-muted small mb-3">ID: {{ $certificate->certificate_id }}</p>
                
                <a href="{{ route('trainee.certificates.show', $certificate->id) }}" class="btn btn-outline-primary w-100 mb-2">View Certificate</a>
                <button class="btn btn-light border w-100" onclick="copyLink('{{ route('verify.certificate', $certificate->certificate_id) }}')">
                    <i class="bi bi-link-45deg"></i> Copy Link
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <div class="display-1 text-muted mb-3"><i class="bi bi-award"></i></div>
        <h5 class="text-muted">You haven't earned any certificates yet.</h5>
        <p class="text-muted">Complete your courses and assessments to earn certificates.</p>
    </div>
    @endforelse
</div>

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).then(function() {
        alert('Verification link copied to clipboard!');
    });
}
</script>
@endsection
