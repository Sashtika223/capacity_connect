@extends('layouts.app')

@section('title', '419 Session Expired - CapacityConnect')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 p-5">
                <div class="mb-4">
                    <span class="badge bg-secondary rounded-pill px-4 py-2 fs-6 mb-3">
                        <i class="bi bi-clock-history me-1"></i> SESSION EXPIRED (HTTP 419)
                    </span>
                    <h1 class="display-4 fw-bold text-dark mb-2">419 Page Expired</h1>
                    <p class="text-muted">Your security token or form session has timed out due to inactivity. Please refresh the page and resubmit your form.</p>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <button onclick="window.location.reload()" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="bi bi-arrow-clockwise me-1"></i> Refresh Session</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
