@extends('layouts.admin')

@section('title', 'Admin - Feedback Moderation')

@section('admin_content')
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Feedback Moderation</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-3">{{ session('success') }}</div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4">Trainee</th>
                                    <th class="py-3">Course</th>
                                    <th class="py-3 text-center">Ratings</th>
                                    <th class="py-3">Comments</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3 text-end px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($feedbacks as $fb)
                                <tr>
                                    <td class="px-4 fw-medium">{{ $fb->user->name }}</td>
                                    <td>{{ $fb->course->title }}</td>
                                    <td class="text-center">
                                        <div class="small">Course: <strong>{{ $fb->course_rating }}</strong>/5</div>
                                        <div class="small">Trainer: <strong>{{ $fb->trainer_rating }}</strong>/5</div>
                                    </td>
                                    <td style="max-width: 250px;">
                                        @if($fb->comments)
                                            <div class="text-truncate small">{{ $fb->comments }}</div>
                                        @else
                                            <span class="text-muted small">No comments</span>
                                        @endif
                                        @if($fb->suggestions)
                                            <div class="text-truncate small text-muted"><em>Idea: {{ $fb->suggestions }}</em></div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($fb->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($fb->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-end px-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if($fb->status !== 'approved')
                                                <form action="{{ route('admin.feedback.update', $fb->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                                </form>
                                            @endif
                                            @if($fb->status !== 'rejected')
                                                <form action="{{ route('admin.feedback.update', $fb->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="btn btn-sm btn-outline-warning">Reject</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.feedback.destroy', $fb->id) }}" method="POST" onsubmit="return confirm('Delete permanently?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No feedback submitted yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
@endsection
