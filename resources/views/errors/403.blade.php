@extends('layouts.app')

@section('title', '403 Forbidden - CapacityConnect')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 p-5">
                <div class="mb-4">
                    <span class="badge bg-danger rounded-pill px-4 py-2 fs-6 mb-3">
                        <i class="bi bi-shield-lock me-1"></i> ACCESS RESTRICTED (HTTP 403)
                    </span>
                    <h1 class="display-4 fw-bold text-dark mb-2">403 Forbidden</h1>
                    <p class="text-muted">You do not have administrative or role clearance to access this resource. Please verify your account privileges or log in with an authorized account.</p>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-pill px-4"><i class="bi bi-arrow-left me-1"></i> Go Back</a>
                    <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="bi bi-house me-1"></i> Return Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
