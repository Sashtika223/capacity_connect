@extends('layouts.admin')

@section('title', 'Trainer Registration Requests')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Trainer Registration Requests</h2>
        <p class="text-muted mb-0">Review, approve, or reject pending trainer registration requests for the portal.</p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Summary Cards Row -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white">
            <h6 class="text-muted fw-bold mb-2 text-uppercase small">Pending Requests</h6>
            <h2 class="fw-bold text-warning mb-0">{{ number_format($pendingCount) }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white">
            <h6 class="text-muted fw-bold mb-2 text-uppercase small">Approved Trainers</h6>
            <h2 class="fw-bold text-success mb-0">{{ number_format($approvedCount) }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white">
            <h6 class="text-muted fw-bold mb-2 text-uppercase small">Rejected Requests</h6>
            <h2 class="fw-bold text-danger mb-0">{{ number_format($rejectedCount) }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white">
            <h6 class="text-muted fw-bold mb-2 text-uppercase small">Total Requests</h6>
            <h2 class="fw-bold text-dark mb-0">{{ number_format($totalCount) }}</h2>
        </div>
    </div>
</div>

<!-- Requests Filter & Search Bar -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <!-- Filter Tabs -->
            <ul class="nav nav-pills gap-2">
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-4 {{ $statusFilter === 'pending' ? 'active bg-primary text-white' : 'text-dark bg-light' }}" 
                       href="{{ route('admin.trainer-requests.index', ['status' => 'pending']) }}">
                        Pending <span class="badge bg-warning text-dark rounded-circle ms-1">{{ $pendingCount }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-4 {{ $statusFilter === 'approved' ? 'active bg-primary text-white' : 'text-dark bg-light' }}" 
                       href="{{ route('admin.trainer-requests.index', ['status' => 'approved']) }}">
                        Approved
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-4 {{ $statusFilter === 'rejected' ? 'active bg-primary text-white' : 'text-dark bg-light' }}" 
                       href="{{ route('admin.trainer-requests.index', ['status' => 'rejected']) }}">
                        Rejected
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-4 {{ $statusFilter === 'all' ? 'active bg-primary text-white' : 'text-dark bg-light' }}" 
                       href="{{ route('admin.trainer-requests.index', ['status' => 'all']) }}">
                        All
                    </a>
                </li>
            </ul>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.trainer-requests.index') }}" class="d-flex gap-2">
                <input type="hidden" name="status" value="{{ $statusFilter }}">
                <input type="text" name="search" class="form-control rounded-pill px-3" placeholder="Search by name or email..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary rounded-pill px-4">Search</button>
            </form>
        </div>
    </div>
</div>

<!-- Requests Table Card -->
<div class="card border-0 shadow-sm rounded-4 mb-5">
    <div class="card-body p-4">
        @if($requests->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Trainer Name</th>
                            <th>Email Address</th>
                            <th>Registered Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $reqUser)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($reqUser->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark">{{ $reqUser->name }}</h6>
                                            <small class="text-muted">Role: {{ ucfirst($reqUser->role) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-medium text-dark">{{ $reqUser->email }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $reqUser->created_at ? $reqUser->created_at->format('M d, Y - h:i A') : 'N/A' }}</small>
                                </td>
                                <td>
                                    @if($reqUser->trainer_status === 'pending')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold">
                                            <i class="bi bi-hourglass-split me-1"></i> Pending
                                        </span>
                                    @elseif($reqUser->trainer_status === 'approved')
                                        <span class="badge bg-success text-white rounded-pill px-3 py-1 fw-bold">
                                            <i class="bi bi-check-circle me-1"></i> Approved
                                        </span>
                                    @elseif($reqUser->trainer_status === 'rejected')
                                        <span class="badge bg-danger text-white rounded-pill px-3 py-1 fw-bold">
                                            <i class="bi bi-x-circle me-1"></i> Rejected
                                        </span>
                                    @else
                                        <span class="badge bg-secondary text-white rounded-pill px-3 py-1">N/A</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <!-- View Details Modal Trigger -->
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 me-1" data-bs-toggle="modal" data-bs-target="#viewModal_{{ $reqUser->id }}">
                                        <i class="bi bi-eye-fill me-1"></i> View
                                    </button>

                                    @if($reqUser->trainer_status === 'pending' || $reqUser->trainer_status === 'rejected')
                                        <button type="button" class="btn btn-sm btn-success rounded-pill px-3 me-1" data-bs-toggle="modal" data-bs-target="#acceptModal_{{ $reqUser->id }}">
                                            <i class="bi bi-check-lg me-1"></i> Accept
                                        </button>
                                    @endif

                                    @if($reqUser->trainer_status === 'pending' || $reqUser->trainer_status === 'approved')
                                        <button type="button" class="btn btn-sm btn-danger rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#rejectModal_{{ $reqUser->id }}">
                                            <i class="bi bi-x-lg me-1"></i> Reject
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            <!-- View Details Modal -->
                            <div class="modal fade" id="viewModal_{{ $reqUser->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold">Trainer Registration Request</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="bg-light p-3 rounded-3 mb-3">
                                                <div class="row g-2">
                                                    <div class="col-4 text-muted small fw-bold">Name:</div>
                                                    <div class="col-8 text-dark fw-bold">{{ $reqUser->name }}</div>
                                                    
                                                    <div class="col-4 text-muted small fw-bold">Email:</div>
                                                    <div class="col-8 text-dark">{{ $reqUser->email }}</div>

                                                    <div class="col-4 text-muted small fw-bold">Registered:</div>
                                                    <div class="col-8 text-dark">{{ $reqUser->created_at ? $reqUser->created_at->format('M d, Y - h:i A') : 'N/A' }}</div>

                                                    <div class="col-4 text-muted small fw-bold">Status:</div>
                                                    <div class="col-8">
                                                        <span class="badge {{ $reqUser->trainer_status === 'approved' ? 'bg-success' : ($reqUser->trainer_status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }} rounded-pill px-3">
                                                            {{ ucfirst($reqUser->trainer_status ?? 'Pending') }}
                                                        </span>
                                                    </div>

                                                    @if($reqUser->approved_at)
                                                        <div class="col-4 text-muted small fw-bold">Approved At:</div>
                                                        <div class="col-8 text-dark">{{ $reqUser->approved_at->format('M d, Y - h:i A') }}</div>
                                                    @endif

                                                    @if($reqUser->rejected_at)
                                                        <div class="col-4 text-muted small fw-bold">Rejected At:</div>
                                                        <div class="col-8 text-dark">{{ $reqUser->rejected_at->format('M d, Y - h:i A') }}</div>
                                                    @endif

                                                    @if($reqUser->rejection_reason)
                                                        <div class="col-4 text-muted small fw-bold">Reason:</div>
                                                        <div class="col-8 text-danger">{{ $reqUser->rejection_reason }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Accept Confirmation Modal -->
                            <div class="modal fade" id="acceptModal_{{ $reqUser->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <form method="POST" action="{{ route('admin.trainer-requests.approve', $reqUser->id) }}">
                                            @csrf
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-success"><i class="bi bi-check-circle-fill me-2"></i> Accept Trainer Registration</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <p class="text-dark mb-0">Are you sure you want to accept the trainer registration request for <strong>{{ $reqUser->name }}</strong> ({{ $reqUser->email }})?</p>
                                                <p class="text-muted small mt-2 mb-0">This will approve the trainer's status, allow them to log in, and dispatch an acceptance email notification.</p>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Accept & Send Approval Email</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Confirmation Modal -->
                            <div class="modal fade" id="rejectModal_{{ $reqUser->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <form method="POST" action="{{ route('admin.trainer-requests.reject', $reqUser->id) }}">
                                            @csrf
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-x-circle-fill me-2"></i> Reject Trainer Registration</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <p class="text-dark mb-3">Are you sure you want to reject the trainer registration request for <strong>{{ $reqUser->name }}</strong> ({{ $reqUser->email }})?</p>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-dark">Rejection Reason (Optional):</label>
                                                    <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Provide a reason for rejecting this request..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Reject & Send Email</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $requests->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary"></i>
                <h5>No Trainer Registration Requests Found</h5>
                <p class="mb-0">There are no trainer requests matching the current status filter.</p>
            </div>
        @endif
    </div>
</div>
@endsection
