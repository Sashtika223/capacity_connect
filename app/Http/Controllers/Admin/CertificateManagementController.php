<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Http\Request;

class CertificateManagementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = Certificate::with(['user', 'course'])
            ->when($status, fn ($q) => $q->where('cert_status', $status))
            ->when($search, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                ->orWhere('certificate_name', 'like', "%{$search}%"));

        $certificates = $query->latest()->paginate(20);

        $stats = [
            'valid' => Certificate::where('cert_status', 'valid')->count(),
            'expiring_soon' => Certificate::where('cert_status', 'expiring_soon')->count(),
            'expired' => Certificate::where('cert_status', 'expired')->count(),
            'renewal_required' => Certificate::where('cert_status', 'renewal_required')->count(),
            'pending_renewal' => Certificate::whereNotNull('renewal_file_path')->where('renewal_verified', false)->count(),
        ];

        return view('dashboards.admin.certificates.index', compact('certificates', 'stats', 'status', 'search'));
    }

    public function verifyRenewal(Request $request, Certificate $certificate)
    {
        $certificate->update([
            'renewal_verified' => true,
            'cert_status' => 'valid',
            'issue_date' => now()->toDateString(),
            'expiry_date' => $request->input('new_expiry_date'),
        ]);

        // Update Competency Profile based on the renewed certificate's course
        if ($certificate->course) {
            $user = $certificate->user;
            foreach ($certificate->course->competencies as $courseComp) {
                $pivot = $user->competencies()->where('competency_id', $courseComp->id)->first();
                $courseLevel = $courseComp->pivot->level ?? 1;

                if ($pivot) {
                    if ($pivot->pivot->current_level < $courseLevel) {
                        $user->competencies()->updateExistingPivot($courseComp->id, [
                            'current_level' => $courseLevel,
                        ]);
                    }
                } else {
                    $user->competencies()->attach($courseComp->id, [
                        'current_level' => $courseLevel,
                        'required_level' => 1,
                    ]);
                }
            }
        }

        // Notify the user
        \DB::table('notifications')->insert([
            'user_id' => $certificate->user_id,
            'type' => 'certificate_verified',
            'message' => "Your renewed certificate '{$certificate->certificate_name}' has been verified by an administrator.",
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Certificate renewal verified and status updated to Valid.');
    }
}
