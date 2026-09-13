@extends('layouts.app')

@section('title', 'Trainee Dashboard')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5 text-center">
                    <h2 class="fw-bold mb-4">Trainee Dashboard</h2>
                    <p class="lead">Welcome back, {{ auth()->user()->name }}!</p>
                    <p class="text-muted">This is a placeholder for the trainee dashboard. You can access your enrolled courses and progress here.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
