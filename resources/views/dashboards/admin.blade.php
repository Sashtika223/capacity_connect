@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse shadow-sm rounded-4 me-md-4 p-3 h-100 min-vh-50">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column gap-2">
                    <li class="nav-item">
                        <a class="nav-link rounded active bg-primary text-white" href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded text-dark" href="{{ route('admin.assessments') }}">
                            Assessments
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-9 px-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5 text-center">
                    <h2 class="fw-bold mb-4">Admin Dashboard</h2>
                    <p class="lead">Welcome back, {{ auth()->user()->name }}!</p>
                    <p class="text-muted">This is a placeholder for the admin dashboard. You can manage the platform here.</p>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
