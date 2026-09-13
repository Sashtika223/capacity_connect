@extends('layouts.admin')

@section('title', 'Admin - Course Management')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Course Management</h2>
    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-2"></i> Create Course
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('admin.courses.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Search Courses</label>
                <input type="text" name="search" class="form-control rounded-3" placeholder="Title or code..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Category</label>
                <select name="category_id" class="form-select rounded-3">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Status</label>
                <select name="status" class="form-select rounded-3">
                    <option value="">All Statuses</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
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
                        <th class="py-3 px-4">Course Info</th>
                        <th class="py-3">Trainer</th>
                        <th class="py-3">Category</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-end px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                    <tr>
                        <td class="px-4">
                            <div class="d-flex align-items-center">
                                @if($course->is_featured)
                                    <i class="bi bi-star-fill text-warning me-2" title="Featured Course"></i>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $course->title }}</div>
                                    <div class="text-muted small">{{ $course->course_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $course->trainer ? $course->trainer->name : 'Unassigned' }}</td>
                        <td>{{ $course->category ? $course->category->name : 'N/A' }}</td>
                        <td>
                            @if($course->status === 'inactive')
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">Archived</span>
                            @elseif($course->publish_status === 'published')
                                <span class="badge bg-success-subtle text-success rounded-pill px-3">Published</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3">Draft</span>
                            @endif
                        </td>
                        <td class="text-end px-4">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light rounded-3" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.courses.edit', $course->id) }}">
                                            <i class="bi bi-pencil-square text-primary me-2"></i> Edit Course
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.courses.toggleFeature', $course->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-star text-warning me-2"></i> 
                                                {{ $course->is_featured ? 'Unfeature' : 'Feature' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.courses.toggleArchive', $course->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-archive text-secondary me-2"></i> 
                                                {{ $course->status === 'inactive' ? 'Unarchive' : 'Archive' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this course?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash text-danger me-2"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
                            No courses found matching your criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($courses->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $courses->links() }}
    </div>
    @endif
</div>
@endsection
