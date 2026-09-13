@extends('layouts.admin')

@section('title', 'Admin - User Management')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">User Management</h2>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Search</label>
                <input type="text" name="search" class="form-control rounded-3" placeholder="Name or email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Role</label>
                <select name="role" class="form-select rounded-3">
                    <option value="">All Roles</option>
                    <option value="trainee" {{ request('role') == 'trainee' ? 'selected' : '' }}>Trainee</option>
                    <option value="trainer" {{ request('role') == 'trainer' ? 'selected' : '' }}>Trainer</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Status</label>
                <select name="status" class="form-select rounded-3">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 rounded-3">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4">Name</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Role</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Joined</th>
                        <th class="py-3 text-end px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="px-4 fw-medium">{{ $user->name }}</td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-3">Admin</span>
                            @elseif($user->role === 'trainer')
                                <span class="badge bg-info-subtle text-info rounded-pill px-3">Trainer</span>
                            @else
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3">Trainee</span>
                            @endif
                        </td>
                        <td>
                            @if($user->status === 'active')
                                <span class="badge bg-success-subtle text-success rounded-pill px-3">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">Inactive</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="text-end px-4">
                            @if($user->role !== 'admin' && $user->id !== auth()->id())
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light rounded-3" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li><h6 class="dropdown-header">Manage User</h6></li>
                                        <li>
                                            <form action="{{ route('admin.users.updateStatus', $user->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $user->status === 'active' ? 'inactive' : 'active' }}">
                                                <button type="submit" class="dropdown-item">
                                                    @if($user->status === 'active')
                                                        <i class="bi bi-person-dash text-warning me-2"></i> Deactivate
                                                    @else
                                                        <i class="bi bi-person-check text-success me-2"></i> Activate
                                                    @endif
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><h6 class="dropdown-header">Change Role</h6></li>
                                        <li>
                                            <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="role" value="trainee">
                                                <button type="submit" class="dropdown-item {{ $user->role === 'trainee' ? 'active' : '' }}">Make Trainee</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="role" value="trainer">
                                                <button type="submit" class="dropdown-item {{ $user->role === 'trainer' ? 'active' : '' }}">Make Trainer</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-3"></i>
                            No users found matching your criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
