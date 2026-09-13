@extends('layouts.app')

@section('title', 'Trainer Portal')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse shadow-sm rounded-4 me-md-4 p-3 h-100 min-vh-50">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column gap-2">
                    <li class="nav-item">
                        <a class="nav-link rounded {{ request()->routeIs('trainer.dashboard') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('trainer.dashboard') }}">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded {{ request()->routeIs('trainer.courses*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('trainer.courses.index') }}">
                            My Courses
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-9 px-md-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('trainer_content')
        </main>
    </div>
</div>
@endsection