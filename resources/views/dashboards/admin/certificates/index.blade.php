@extends('layouts.admin')

@section('title', 'Certificate Management')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="fw-bold"><i class="bi bi-patch-check text-primary me-2"></i> Certificate Management</h2>
        <p class="text-muted mb-0">Monitor, validate, and manage all employee certifications. Track expiry and renewals.</p>
    </div>
    <a href="{{ route('admin.certificate-management.index') }}?run_check=1" class="btn btn-outline-primary rounded-pill px-4">
        <i class="bi bi-arrow-clockwise me-2"></i> Refresh Statuses
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

{{-- Status Overview Cards --}}
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <a href="{{ route('admin.certificate-management.index') }}?status=valid" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-success-subtle border-start border-success border-5">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-patch-check-fill text-success fs-1 mb-2"></i>
                    <div class="display-6 fw-bold text-success">{{ $stats['valid'] }}</div>
                    <div class="fw-bold text-success mt-1">Valid</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('admin.certificate-management.index') }}?status=expiring_soon" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-warning-subtle border-start border-warning border-5">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-exclamation-triangle-fill text-warning fs-1 mb-2"></i>
                    <div class="display-6 fw-bold text-warning-emphasis">{{ $stats['expiring_soon'] }}</div>
                    <div class="fw-bold text-warning-emphasis mt-1">Expiring Soon</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('admin.certificate-management.index') }}?status=expired" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-danger-subtle border-start border-danger border-5">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-x-circle-fill text-danger fs-1 mb-2"></i>
                    <div class="display-6 fw-bold text-danger">{{ $stats['expired'] }}</div>
                    <div class="fw-bold text-danger mt-1">Expired</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('admin.certificate-management.index') }}?status=renewal_required" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-info-subtle border-start border-info border-5">
                <div class="card-body p-4 text-center">
                    <i class="bi bi-upload text-info fs-1 mb-2"></i>
                    <div class="display-6 fw-bold text-info-emphasis">{{ $stats['pending_renewal'] }}</div>
                    <div class="fw-bold text-info-emphasis mt-1">Awaiting Verification</div>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.certificate-management.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-bold small text-muted text-uppercase">Search Employee or Certificate</label>
                <input type="text" name="search" class="form-control" placeholder="Name, certificate name..." value="{{ $search }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold small text-muted text-uppercase">Status Filter</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="valid" {{ $status === 'valid' ? 'selected' : '' }}>Valid</option>
                    <option value="expiring_soon" {{ $status === 'expiring_soon' ? 'selected' : '' }}>Expiring Soon</option>
                    <option value="expired" {{ $status === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="renewal_required" {{ $status === 'renewal_required' ? 'selected' : '' }}>Renewal Required</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary rounded-pill w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- Certificates Table --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4">Employee</th>
                        <th class="py-3">Certificate</th>
                        <th class="py-3">Issued By</th>
                        <th class="py-3 text-center">Issue Date</th>
                        <th class="py-3 text-center">Expiry Date</th>
                        <th class="py-3 text-center">Days Left</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $cert)
                    @php
                        $daysLeft = $cert->expiry_date ? now()->diffInDays($cert->expiry_date, false) : null;
                        $statusColors = [
                            'valid'            => 'success',
                            'expiring_soon'    => 'warning',
                            'expired'          => 'danger',
                            'renewal_required' => 'info',
                        ];
                        $color = $statusColors[$cert->cert_status] ?? 'secondary';
                    @endphp
                    <tr>
                        <td class="px-4">
                            <div class="fw-bold">{{ $cert->user->name ?? 'Unknown' }}</div>
                            <div class="text-muted small text-capitalize">{{ $cert->user->role ?? '' }}</div>
                        </td>
                        <td>
                            <div class="fw-medium">{{ $cert->certificate_name ?? $cert->course?->title ?? "Cert #{$cert->certificate_id}" }}</div>
                            <div class="text-muted small font-monospace">ID: {{ $cert->certificate_id }}</div>
                        </td>
                        <td class="text-muted small">{{ $cert->issuing_organization ?? $cert->course?->trainer?->name ?? '—' }}</td>
                        <td class="text-center small">{{ $cert->issue_date?->format('d M Y') ?? '—' }}</td>
                        <td class="text-center small {{ $daysLeft !== null && $daysLeft < 30 ? 'text-danger fw-bold' : '' }}">
                            {{ $cert->expiry_date?->format('d M Y') ?? 'No Expiry' }}
                        </td>
                        <td class="text-center">
                            @if($daysLeft === null)
                                <span class="text-muted">—</span>
                            @elseif($daysLeft < 0)
                                <span class="text-danger fw-bold">Expired</span>
                            @else
                                <span class="fw-bold text-{{ $color }}">{{ $daysLeft }}d</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $color }}-subtle text-{{ $color }} border border-{{ $color }} rounded-pill px-3">
                                {{ $cert->statusLabel() }}
                            </span>
                        </td>
                        <td class="px-4 text-center">
                            @if($cert->renewal_file_path && !$cert->renewal_verified)
                                <button class="btn btn-sm btn-info rounded-pill text-white px-2" data-bs-toggle="modal" data-bs-target="#verifyModal{{ $cert->id }}">
                                    <i class="bi bi-shield-check me-1"></i> Verify Renewal
                                </button>
                                {{-- Modal --}}
                                <div class="modal fade" id="verifyModal{{ $cert->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title fw-bold">Verify Renewal — {{ $cert->user->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-muted mb-3">Review the uploaded document, then enter the new expiry date to verify.</p>
                                                <a href="{{ Storage::url($cert->renewal_file_path) }}" target="_blank" class="btn btn-outline-secondary rounded-pill mb-3 w-100">
                                                    <i class="bi bi-file-earmark-arrow-down me-2"></i> View Uploaded Document
                                                </a>
                                                <form action="{{ route('admin.certificate-management.verify-renewal', $cert->id) }}" method="POST">
                                                    @csrf
                                                    <label class="form-label fw-bold">New Expiry Date</label>
                                                    <input type="date" name="new_expiry_date" class="form-control mb-3" required min="{{ now()->toDateString() }}">
                                                    <button type="submit" class="btn btn-success rounded-pill w-100 fw-bold">
                                                        <i class="bi bi-patch-check me-2"></i> Confirm & Verify
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cert->renewal_verified)
                                <span class="text-success small"><i class="bi bi-check2-circle me-1"></i>Verified</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-patch-check fs-1 d-block mb-3"></i>
                            No certificates found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($certificates->hasPages())
            <div class="p-4">{{ $certificates->links() }}</div>
        @endif
    </div>
</div>
@endsection
