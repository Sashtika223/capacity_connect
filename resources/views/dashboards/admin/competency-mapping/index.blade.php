@extends('layouts.admin')

@section('title', 'Admin - Competency Mapping')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Competency Mapping</h2>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<ul class="nav nav-tabs mb-4 border-0" id="mappingTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill px-4 me-2 border-0 fw-bold bg-light" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" style="color: #495057;">User Mapping</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4 border-0 fw-bold bg-light" id="courses-tab" data-bs-toggle="tab" data-bs-target="#courses" type="button" role="tab" style="color: #495057;">Course Mapping</button>
    </li>
</ul>

<style>
    .nav-tabs .nav-link.active {
        background-color: var(--bs-primary) !important;
        color: white !important;
    }
</style>

<div class="tab-content" id="mappingTabsContent">
    <!-- User Mapping Tab -->
    <div class="tab-pane fade show active" id="users" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-4">User Name</th>
                                <th class="py-3">Role</th>
                                <th class="py-3">Mapped Competencies</th>
                                <th class="py-3 text-end px-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="px-4 fw-medium">{{ $user->name }}</td>
                                <td><span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 text-capitalize">{{ $user->role }}</span></td>
                                <td>
                                    @if($user->competencies->count() > 0)
                                        <span class="badge bg-info-subtle text-info rounded-pill px-3">{{ $user->competencies->count() }}</span>
                                    @else
                                        <span class="text-muted small">None</span>
                                    @endif
                                </td>
                                <td class="text-end px-4">
                                    <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#mapUserModal{{ $user->id }}">
                                        Map Skills
                                    </button>
                                </td>
                            </tr>

                            <!-- Map User Modal -->
                            <div class="modal fade" id="mapUserModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content rounded-4 border-0">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold">Map Competencies: {{ $user->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.competency-mapping.mapUser') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <div class="modal-body py-4" style="max-height: 60vh; overflow-y: auto;">
                                                <div class="table-responsive">
                                                    <table class="table align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th>Select</th>
                                                                <th>Competency</th>
                                                                <th>Current Level</th>
                                                                <th>Required Level</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($competencies as $competency)
                                                            @php
                                                                $mapped = $user->competencies->where('id', $competency->id)->first();
                                                                $isChecked = $mapped ? true : false;
                                                                $current = $mapped ? $mapped->pivot->current_level : 0;
                                                                $required = $mapped ? $mapped->pivot->required_level : 1;
                                                            @endphp
                                                            <tr>
                                                                <td>
                                                                    <input class="form-check-input" type="checkbox" name="competencies[]" value="{{ $competency->id }}" {{ $isChecked ? 'checked' : '' }}>
                                                                </td>
                                                                <td class="fw-medium">{{ $competency->name }}</td>
                                                                <td>
                                                                    <select name="current_levels[{{ $competency->id }}]" class="form-select form-select-sm rounded-3">
                                                                        <option value="0" {{ $current == 0 ? 'selected' : '' }}>None</option>
                                                                        <option value="1" {{ $current == 1 ? 'selected' : '' }}>Beginner</option>
                                                                        <option value="2" {{ $current == 2 ? 'selected' : '' }}>Intermediate</option>
                                                                        <option value="3" {{ $current == 3 ? 'selected' : '' }}>Advanced</option>
                                                                        <option value="4" {{ $current == 4 ? 'selected' : '' }}>Expert</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select name="required_levels[{{ $competency->id }}]" class="form-select form-select-sm rounded-3">
                                                                        <option value="1" {{ $required == 1 ? 'selected' : '' }}>Beginner</option>
                                                                        <option value="2" {{ $required == 2 ? 'selected' : '' }}>Intermediate</option>
                                                                        <option value="3" {{ $required == 3 ? 'selected' : '' }}>Advanced</option>
                                                                        <option value="4" {{ $required == 4 ? 'selected' : '' }}>Expert</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">Save Mapping</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Mapping Tab -->
    <div class="tab-pane fade" id="courses" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-4">Course Title</th>
                                <th class="py-3">Code</th>
                                <th class="py-3">Teaches Competencies</th>
                                <th class="py-3 text-end px-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                            <tr>
                                <td class="px-4 fw-medium">{{ $course->title }}</td>
                                <td>{{ $course->course_code }}</td>
                                <td>
                                    @if($course->competencies->count() > 0)
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3">{{ $course->competencies->count() }}</span>
                                    @else
                                        <span class="text-muted small">None</span>
                                    @endif
                                </td>
                                <td class="text-end px-4">
                                    <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#mapCourseModal{{ $course->id }}">
                                        Map Skills
                                    </button>
                                </td>
                            </tr>

                            <!-- Map Course Modal -->
                            <div class="modal fade" id="mapCourseModal{{ $course->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content rounded-4 border-0">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold">Map Competencies: {{ $course->title }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.competency-mapping.mapCourse') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                                            <div class="modal-body py-4" style="max-height: 60vh; overflow-y: auto;">
                                                <div class="table-responsive">
                                                    <table class="table align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th>Select</th>
                                                                <th>Competency</th>
                                                                <th>Level Granted</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($competencies as $competency)
                                                            @php
                                                                $mapped = $course->competencies->where('id', $competency->id)->first();
                                                                $isChecked = $mapped ? true : false;
                                                                $level = $mapped ? $mapped->pivot->level : 1;
                                                            @endphp
                                                            <tr>
                                                                <td>
                                                                    <input class="form-check-input" type="checkbox" name="competencies[]" value="{{ $competency->id }}" {{ $isChecked ? 'checked' : '' }}>
                                                                </td>
                                                                <td class="fw-medium">{{ $competency->name }}</td>
                                                                <td>
                                                                    <select name="levels[{{ $competency->id }}]" class="form-select form-select-sm rounded-3">
                                                                        <option value="1" {{ $level == 1 ? 'selected' : '' }}>Beginner</option>
                                                                        <option value="2" {{ $level == 2 ? 'selected' : '' }}>Intermediate</option>
                                                                        <option value="3" {{ $level == 3 ? 'selected' : '' }}>Advanced</option>
                                                                        <option value="4" {{ $level == 4 ? 'selected' : '' }}>Expert</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">Save Mapping</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
