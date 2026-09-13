@extends('layouts.app')

@section('title', '404 Page Not Found - CapacityConnect')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 p-5">
                <div class="mb-4">
                    <span class="badge bg-warning text-dark rounded-pill px-4 py-2 fs-6 mb-3">
                        <i class="bi bi-search me-1"></i> NOT FOUND (HTTP 404)
                    </span>
                    <h1 class="display-4 fw-bold text-dark mb-2">404 Page Not Found</h1>
                    <p class="text-muted">The requested portal page, course module, or resource could not be located. It may have been archived, renamed, or removed.</p>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('search.index') }}" class="btn btn-outline-primary rounded-pill px-4"><i class="bi bi-search me-1"></i> Search Portal</a>
                    <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="bi bi-house me-1"></i> Return Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
