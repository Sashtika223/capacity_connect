@extends('layouts.app')

@section('title', 'Certificate Verification')

@section('content')
<div class="container py-5 min-vh-100 d-flex flex-column align-items-center mt-5">
    
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">CAPACITY CONNECT</h2>
        <p class="text-muted fs-5">Official Certificate Verification</p>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="max-width: 600px; width: 100%;">
        @if($certificate)
            <!-- Valid Certificate -->
            <div class="bg-success text-white text-center py-4">
                <i class="bi bi-patch-check-fill mb-2" style="font-size: 4rem;"></i>
                <h3 class="fw-bold mb-0">Certificate is Valid</h3>
                <p class="mb-0 opacity-75">This is an authentic CAPACITY CONNECT certificate.</p>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted" width="35%">Certificate ID</td>
                            <td class="fw-bold fs-5">{{ $certificate->certificate_id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Issued To</td>
                            <td class="fw-bold fs-5">{{ $certificate->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Course</td>
                            <td class="fw-bold text-primary">{{ $certificate->course->title }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Issue Date</td>
                            <td class="fw-bold">{{ $certificate->issue_date->format('F d, Y') }}</td>
                        </tr>
                        @if($certificate->score)
                        <tr>
                            <td class="text-muted">Assessment Score</td>
                            <td class="fw-bold">{{ $certificate->score }}%</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <div class="alert alert-info mt-4 mb-0 small">
                    <i class="bi bi-info-circle me-2"></i> This information is publicly verified. To protect user privacy, contact details and other profile information are not displayed here.
                </div>
            </div>
        @else
            <!-- Invalid Certificate -->
            <div class="bg-danger text-white text-center py-5">
                <i class="bi bi-x-circle-fill mb-2" style="font-size: 4rem;"></i>
                <h3 class="fw-bold mb-0">Invalid Certificate</h3>
                <p class="mb-0 mt-2 opacity-75">We could not find a certificate matching the ID:</p>
                <code class="d-inline-block bg-white text-danger px-3 py-2 rounded mt-2 fs-5">{{ $certificateId }}</code>
            </div>
            
            <div class="card-body p-5 text-center">
                <p class="text-muted mb-0">Please ensure you have copied the exact Certificate ID or URL correctly. If you believe this is an error, please contact support.</p>
            </div>
        @endif
    </div>

    <div class="mt-5 text-center">
        <a href="/" class="btn btn-outline-secondary rounded-pill px-4">Return to Homepage</a>
    </div>

</div>
@endsection
