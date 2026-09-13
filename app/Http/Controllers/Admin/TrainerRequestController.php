<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TrainerApprovedMail;
use App\Mail\TrainerRejectedMail;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\HttpMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrainerRequestController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->get('status', 'pending');

        $query = User::where('role', 'trainer')->with(['trainerProfile', 'approver']);

        if ($statusFilter !== 'all') {
            $query->where('trainer_status', $statusFilter);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $requests = $query->latest()->paginate(15);

        // Institutional Counts
        $pendingCount = User::where('role', 'trainer')->where('trainer_status', 'pending')->count();
        $approvedCount = User::where('role', 'trainer')->where('trainer_status', 'approved')->count();
        $rejectedCount = User::where('role', 'trainer')->where('trainer_status', 'rejected')->count();
        $totalCount = User::where('role', 'trainer')->count();

        return view('dashboards.admin.trainer-requests.index', compact(
            'requests', 'statusFilter', 'pendingCount', 'approvedCount', 'rejectedCount', 'totalCount'
        ));
    }

    public function approve(Request $request, User $user)
    {
        if (! $user->isTrainer()) {
            return back()->with('error', 'Selected user is not a trainer.');
        }

        $user->update([
            'trainer_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Send Acceptance Email (HTTPS API with SMTP/Log Fallback)
        HttpMailService::send($user->email, new TrainerApprovedMail($user), $user->name);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'TRAINER_REGISTRATION_APPROVED',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'new_values' => ['trainer_email' => $user->email, 'trainer_name' => $user->name],
        ]);

        return back()->with('success', "Trainer registration request for {$user->name} has been approved successfully. Email notification sent.");
    }

    public function reject(Request $request, User $user)
    {
        if (! $user->isTrainer()) {
            return back()->with('error', 'Selected user is not a trainer.');
        }

        $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $reason = $request->rejection_reason;

        $user->update([
            'trainer_status' => 'rejected',
            'status' => 'inactive',
            'rejected_at' => now(),
            'rejection_reason' => $reason,
            'approved_by' => auth()->id(),
        ]);

        // Send Rejection Email (HTTPS API with SMTP/Log Fallback)
        HttpMailService::send($user->email, new TrainerRejectedMail($user, $reason), $user->name);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'TRAINER_REGISTRATION_REJECTED',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'new_values' => ['trainer_email' => $user->email, 'reason' => $reason],
        ]);

        return back()->with('success', "Trainer registration request for {$user->name} has been rejected. Email notification sent.");
    }
}
