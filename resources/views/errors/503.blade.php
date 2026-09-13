@extends('layouts.app')

@section('title', '503 Maintenance - CapacityConnect')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 p-5">
                <div class="mb-4">
                    <span class="badge bg-warning text-dark rounded-pill px-4 py-2 fs-6 mb-3">
                        <i class="bi bi-tools me-1"></i> MAINTENANCE (HTTP 503)
                    </span>
                    <h1 class="display-4 fw-bold text-dark mb-2">Service Maintenance</h1>
                    <p class="text-muted">CapacityConnect LMS is undergoing scheduled system updates and database maintenance. Service will resume shortly.</p>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <button onclick="window.location.reload()" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="bi bi-arrow-clockwise me-1"></i> Check Status</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
