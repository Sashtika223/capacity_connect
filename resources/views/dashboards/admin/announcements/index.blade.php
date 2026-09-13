@extends('layouts.admin')

@section('title', 'Admin - Announcement & Broadcast Center')

@section('admin_content')
<div class="container-fluid py-2">
    <!-- Distinct Admin Header Banner -->
    <div class="card border-0 rounded-4 shadow-sm mb-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0d6efd 100%);">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 position-relative z-1">
                <div>
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-15 border border-white border-opacity-20 text-white small fw-semibold mb-3">
                        <i class="bi bi-shield-lock-fill text-warning"></i> ADMIN CONTROL CENTER &bull; SYSTEM BROADCASTS
                    </div>
                    <h2 class="fw-extrabold mb-2 display-6" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        <i class="bi bi-megaphone-fill me-2 text-info"></i> Announcement & Broadcast Center
                    </h2>
                    <p class="text-white-50 mb-0 max-w-700 fs-6">
                        Create, schedule, edit, and target system-wide operational announcements, emergency advisories, and workforce bulletins across CapacityConnect LMS.
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.announcements.create') }}" class="btn btn-warning btn-lg rounded-pill px-4 fw-bold shadow">
                        <i class="bi bi-plus-circle-fill me-2"></i> Create New Announcement
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4 p-3 d-flex align-items-center">
            <i class="bi bi-check-circle-fill fs-4 text-success me-3"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Telemetry Cards for Admin -->
    @php
        $totalCount = \App\Models\Announcement::count();
        $publishedCount = \App\Models\Announcement::where('is_published', true)->count();
        $draftCount = \App\Models\Announcement::where('is_published', false)->count();
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase fs-7">Total Announcements</small>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ $totalCount }}</h3>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-4 fs-3">
                        <i class="bi bi-collection-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase fs-7">Live / Published</small>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ $publishedCount }}</h3>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 text-success rounded-4 fs-3">
                        <i class="bi bi-broadcast-pin"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase fs-7">Drafts / Scheduled</small>
                        <h3 class="fw-bold text-secondary mb-0 mt-1">{{ $draftCount }}</h3>
                    </div>
                    <div class="p-3 bg-secondary bg-opacity-10 text-secondary rounded-4 fs-3">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Data Table -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-table me-2 text-primary"></i> Broadcast Inventory Table</h5>
            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small"><i class="bi bi-info-circle me-1"></i> Management View Only</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">Title & Details</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Published / Scheduled Date</th>
                            <th class="py-3">Expiry Date</th>
                            <th class="py-3">Author</th>
                            <th class="py-3 text-end px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $announcement)
                        <tr>
                            <td class="px-4">
                                <div class="fw-bold text-dark fs-6 mb-1">{{ $announcement->title }}</div>
                                <div class="text-muted small text-truncate" style="max-width: 380px;">{{ $announcement->content }}</div>
                            </td>
                            <td>
                                @if($announcement->is_published)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-semibold">
                                        <i class="bi bi-check-circle-fill me-1"></i> Published
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1 fw-semibold">
                                        <i class="bi bi-clock-history me-1"></i> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ $announcement->published_at ? $announcement->published_at->format('M d, Y h:i A') : 'Immediate' }}
                            </td>
                            <td class="text-muted small">
                                <i class="bi bi-calendar-x me-1"></i>
                                {{ $announcement->expires_at ? $announcement->expires_at->format('M d, Y') : 'Never' }}
                            </td>
                            <td class="text-muted small">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width:28px; height:28px; font-size:12px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <span>{{ $announcement->creator->name ?? 'System Admin' }}</span>
                                </div>
                            </td>
                            <td class="text-end px-4">
                                <div class="btn-group shadow-xs rounded-3">
                                    <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Announcement">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-start-0" title="Delete Announcement">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="bi bi-megaphone display-4 text-secondary opacity-50 d-block mb-3"></i>
                                    <h5 class="fw-bold text-dark">No System Announcements Found</h5>
                                    <p class="small text-muted mb-3">Create your first broadcast to publish official operational notices to LMS personnel.</p>
                                    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary rounded-pill px-4">
                                        <i class="bi bi-plus-lg me-1"></i> Create Announcement
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($announcements->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
