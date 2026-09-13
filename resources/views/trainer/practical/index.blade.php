@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 font-weight-bold" style="color: #000000 !important;"><i class="fas fa-shield-alt mr-2" style="color: #000000 !important;"></i>Practical Assessment Drills</h1>
            <p class="mb-0" style="color: #000000 !important;">Design interactive branching scenarios for disaster response & emergency decision drills.</p>
        </div>
        <a href="{{ route('trainer.practical.create') }}" class="btn btn-dark shadow-sm" style="color: #ffffff !important; background-color: #000000 !important; border-color: #000000 !important;">
            <i class="fas fa-plus mr-1"></i> Create New Drill
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="color: #000000 !important;">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: #000000 !important;">
                    <thead class="bg-light">
                        <tr>
                            <th style="color: #000000 !important; font-weight: 700;">Drill Title</th>
                            <th style="color: #000000 !important; font-weight: 700;">Associated Course</th>
                            <th style="color: #000000 !important; font-weight: 700;">Scenario Steps</th>
                            <th style="color: #000000 !important; font-weight: 700;">Status</th>
                            <th style="color: #000000 !important; font-weight: 700;">Created Date</th>
                            <th class="text-right" style="color: #000000 !important; font-weight: 700;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assessments as $assessment)
                            <tr style="color: #000000 !important;">
                                <td style="color: #000000 !important;">
                                    <strong style="color: #000000 !important;">{{ $assessment->title }}</strong>
                                    <div class="small" style="color: #000000 !important;">{{ Str::limit($assessment->description, 60) }}</div>
                                </td>
                                <td style="color: #000000 !important;">
                                    @if($assessment->course)
                                        <span class="badge border border-dark text-dark bg-light" style="color: #000000 !important;">{{ $assessment->course->title }}</span>
                                    @else
                                        <span style="color: #000000 !important;">General Drill</span>
                                    @endif
                                </td>
                                <td style="color: #000000 !important;">
                                    <span class="badge badge-pill border border-dark text-dark bg-light" style="color: #000000 !important;">{{ $assessment->nodes->count() }} nodes</span>
                                </td>
                                <td style="color: #000000 !important;">
                                    @if($assessment->status === 'published')
                                        <span class="badge border border-dark text-dark bg-light" style="color: #000000 !important;">Published</span>
                                    @else
                                        <span class="badge border border-dark text-dark bg-light" style="color: #000000 !important;">Draft</span>
                                    @endif
                                </td>
                                <td style="color: #000000 !important; font-weight: 600;">{{ $assessment->created_at->format('M d, Y') }}</td>
                                <td class="text-right" style="color: #000000 !important;">
                                    <a href="{{ route('trainer.practical.show', $assessment) }}" class="btn btn-sm btn-outline-dark mr-1 font-weight-bold" style="color: #000000 !important; border-color: #000000 !important;" title="Build/Manage Nodes">
                                        <i class="fas fa-sitemap mr-1" style="color: #000000 !important;"></i> Manage Nodes
                                    </a>
                                    <a href="{{ route('trainer.practical.edit', $assessment) }}" class="btn btn-sm btn-outline-dark mr-1" style="color: #000000 !important; border-color: #000000 !important;">
                                        <i class="fas fa-edit" style="color: #000000 !important;"></i>
                                    </a>
                                    <form action="{{ route('trainer.practical.destroy', $assessment) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this drill?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-dark" style="color: #000000 !important; border-color: #000000 !important;"><i class="fas fa-trash" style="color: #000000 !important;"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5" style="color: #000000 !important;">
                                    <i class="fas fa-folder-open fa-3x mb-3" style="color: #000000 !important;"></i>
                                    <p class="mb-0" style="color: #000000 !important;">No practical response drills created yet. Click "Create New Drill" to begin.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $assessments->links() }}
    </div>
</div>
@endsection
