@extends('layouts.app')

@section('title', '429 Rate Limit Exceeded - CapacityConnect')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 p-5">
                <div class="mb-4">
                    <span class="badge bg-danger rounded-pill px-4 py-2 fs-6 mb-3">
                        <i class="bi bi-speedometer me-1"></i> RATE LIMIT EXCEEDED (HTTP 429)
                    </span>
                    <h1 class="display-4 fw-bold text-dark mb-2">429 Too Many Requests</h1>
                    <p class="text-muted">You have submitted too many requests in a short time frame. Rate limiting is enforced to ensure server security and performance stability. Please wait 60 seconds before trying again.</p>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="bi bi-house me-1"></i> Return Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
