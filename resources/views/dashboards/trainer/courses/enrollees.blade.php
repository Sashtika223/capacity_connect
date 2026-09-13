@extends('layouts.trainer')

@section('trainer_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Enrollees: {{ $course->title }}</h2>
    <a href="{{ route('trainer.courses.index') }}" class="btn btn-outline-secondary">Back to Courses</a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        @if($enrollments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Trainee Name</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Enrolled On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $enrollment)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $enrollment->user->name }}</div>
                                <div class="small text-muted">{{ $enrollment->user->email }}</div>
                            </td>
                            <td>{{ $enrollment->user->traineeProfile->department ?? 'N/A' }}</td>
                            <td>
                                @if($enrollment->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-primary">Enrolled</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $enrollment->progress }}%;"></div>
                                    </div>
                                    <span class="small">{{ $enrollment->progress }}%</span>
                                </div>
                            </td>
                            <td>{{ $enrollment->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <h5 class="text-muted">No trainees enrolled in this course yet.</h5>
            </div>
        @endif
    </div>
</div>
@endsection
