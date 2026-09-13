@extends('layouts.admin')

@section('title', 'System Audit Logs')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold"><i class="bi bi-shield-check text-primary me-2"></i> Security & Audit Logs</h2>
        <p class="text-muted mb-0">Track all administrative, course, user, and security actions with full IP & payload metadata.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="action" class="form-control rounded-pill" placeholder="Filter by action (e.g. Course published, User suspended)..." value="{{ request('action') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-search me-1"></i> Filter Logs</button>
                <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-secondary rounded-pill">Reset</a>
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
                        <th class="py-3 px-4">Timestamp</th>
                        <th class="py-3">User</th>
                        <th class="py-3">Action</th>
                        <th class="py-3">Target Entity</th>
                        <th class="py-3">IP Address</th>
                        <th class="py-3 px-4">Metadata</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="px-4 text-muted small">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                            <td>
                                @if($log->user)
                                    <strong class="text-dark">{{ $log->user->name }}</strong>
                                    <div class="small text-muted">{{ $log->user->role }}</div>
                                @else
                                    <span class="text-muted">System / Guest</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill font-monospace fw-bold">{{ $log->action }}</span>
                            </td>
                            <td>
                                @if($log->entity_type)
                                    <span class="small font-monospace text-dark">{{ class_basename($log->entity_type) }} #{{ $log->entity_id }}</span>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td class="font-monospace small text-muted">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                            <td class="px-4">
                                @if($log->previous_values || $log->new_values)
                                    <button class="btn btn-xs btn-outline-secondary rounded-pill" type="button" data-bs-toggle="collapse" data-bs-target="#payload{{ $log->id }}">
                                        View Changes
                                    </button>
                                    <div class="collapse mt-2" id="payload{{ $log->id }}">
                                        <pre class="bg-light p-2 rounded small mb-0" style="max-width: 300px; max-height: 150px; overflow: auto;">{{ json_encode(['prev' => $log->previous_values, 'new' => $log->new_values], JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                @else
                                    <span class="text-muted small">None</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No audit logs recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $logs->links() }}
</div>
@endsection
