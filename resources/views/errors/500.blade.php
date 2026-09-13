@extends('layouts.app')

@section('title', '500 Server Error - CapacityConnect')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 p-5">
                <div class="mb-4">
                    <span class="badge bg-danger rounded-pill px-4 py-2 fs-6 mb-3">
                        <i class="bi bi-exclamation-triangle me-1"></i> SYSTEM ERROR (HTTP 500)
                    </span>
                    <h1 class="display-4 fw-bold text-dark mb-2">500 Internal Error</h1>
                    <p class="text-muted">An internal server error occurred while processing your request. The technical exception has been logged for system administrator review.</p>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="bi bi-house me-1"></i> Return Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
