@extends('layouts.app')

@section('title', 'Trainee Portal')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse shadow-sm rounded-4 me-md-4 p-3 h-100 min-vh-50">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column gap-2">
                    <li class="nav-item">
                        <a class="nav-link rounded {{ request()->routeIs('trainee.dashboard') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('trainee.dashboard') }}">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded {{ request()->routeIs('trainee.profile') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('trainee.profile') }}">
                            My Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded {{ request()->routeIs('trainee.courses*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('trainee.courses') }}">
                            Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded {{ request()->routeIs('trainee.enrollments') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('trainee.enrollments') }}">
                            Enrollments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded {{ request()->routeIs('trainee.assessments') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('trainee.assessments') }}">
                            Assessments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded {{ request()->routeIs('trainee.results') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('trainee.results') }}">
                            Results
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded {{ request()->routeIs('trainee.certificates') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('trainee.certificates') }}">
                            Certificates
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded {{ request()->routeIs('trainee.feedback') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('trainee.feedback') }}">
                            Feedback
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded {{ request()->routeIs('trainee.competencies*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('trainee.competencies.index') }}">
                            Competencies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded {{ request()->routeIs('trainee.notifications') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('trainee.notifications') }}">
                            Notifications
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-9 px-md-4">
            @yield('trainee_content')
        </main>
    </div>
</div>
@endsection