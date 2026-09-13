@extends('layouts.admin')

@section('title', 'Admin - Competencies')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Competency Framework</h2>
    <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#createCompetencyModal">
        <i class="bi bi-plus-lg me-2"></i> Add Competency
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4">Name</th>
                        <th class="py-3">Type</th>
                        <th class="py-3">Description</th>
                        <th class="py-3 text-end px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($competencies as $competency)
                    <tr>
                        <td class="px-4 fw-medium">{{ $competency->name }}</td>
                        <td>
                            @php $typeVal = strtolower($competency->type ?: 'technical'); @endphp
                            @if($typeVal === 'technical')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 text-capitalize">
                                    <i class="bi bi-gear-fill me-1"></i> Technical
                                </span>
                            @elseif($typeVal === 'behavioral')
                                <span class="badge rounded-pill px-3 py-1 text-capitalize" style="background-color: #F3E8FF; color: #7E22CE; border: 1px solid #E9D5FF;">
                                    <i class="bi bi-person-fill me-1"></i> Behavioral
                                </span>
                            @elseif($typeVal === 'leadership')
                                <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-3 py-1 text-capitalize">
                                    <i class="bi bi-award-fill me-1"></i> Leadership
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 text-capitalize">
                                    <i class="bi bi-shield-check me-1"></i> {{ ucfirst($typeVal) }}
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small text-truncate" style="max-width: 300px;">
                            {{ $competency->description ?: 'N/A' }}
                        </td>
                        <td class="text-end px-4">
                            <button type="button" class="btn btn-sm btn-light rounded-3 me-2" data-bs-toggle="modal" data-bs-target="#editCompetencyModal{{ $competency->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.competencies.destroy', $competency->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this competency?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger rounded-3">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editCompetencyModal{{ $competency->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold">Edit Competency</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.competencies.update', $competency->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body py-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control rounded-3" value="{{ $competency->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Type <span class="text-danger">*</span></label>
                                            <select name="type" class="form-select rounded-3" required>
                                                <option value="technical" {{ $competency->type === 'technical' ? 'selected' : '' }}>Technical</option>
                                                <option value="behavioral" {{ $competency->type === 'behavioral' ? 'selected' : '' }}>Behavioral</option>
                                                <option value="leadership" {{ $competency->type === 'leadership' ? 'selected' : '' }}>Leadership</option>
                                                <option value="core" {{ $competency->type === 'core' ? 'selected' : '' }}>Core</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Description</label>
                                            <textarea name="description" class="form-control rounded-3" rows="3">{{ $competency->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-bullseye fs-1 d-block mb-3"></i>
                            No competencies defined yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($competencies->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $competencies->links() }}
    </div>
    @endif
</div>

<!-- Create Modal -->
<div class="modal fade" id="createCompetencyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Add New Competency</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.competencies.store') }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select rounded-3" required>
                            <option value="technical">Technical</option>
                            <option value="behavioral">Behavioral</option>
                            <option value="leadership">Leadership</option>
                            <option value="core">Core</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control rounded-3" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Add Competency</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
