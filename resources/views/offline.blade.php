@extends('layouts.app')

@section('title', 'Offline Mode - CapacityConnect')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 p-5">
                <div class="mb-4">
                    <span class="badge bg-secondary rounded-pill px-4 py-2 fs-6 mb-3">
                        <i class="bi bi-wifi-off me-2"></i> OFFLINE MODE
                    </span>
                    <h2 class="fw-bold text-dark mb-2">Network Disconnected</h2>
                    <p class="text-muted">You are currently offline. Field materials and previously cached course documents remain available for your review.</p>
                </div>

                <div class="alert alert-info border-0 rounded-4 text-start mb-4">
                    <h6 class="fw-bold"><i class="bi bi-info-circle me-1"></i> Offline Capabilities:</h6>
                    <ul class="mb-0 small">
                        <li>Access previously downloaded lessons and course resources.</li>
                        <li>Review practical disaster response procedures.</li>
                        <li>Offline progress will automatically synchronize once connection is restored.</li>
                    </ul>
                </div>

                <button onclick="window.location.reload()" class="btn btn-primary rounded-pill px-4 fw-bold">
                    <i class="bi bi-arrow-clockwise me-1"></i> Retry Connection
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
