<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Course;
use App\Models\TrainerCertification;
use App\Models\TrainerCertificationAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TrainerCertificationController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'trainer')->with('trainerProfile', 'trainerCertifications.course');

        if ($request->filled('status')) {
            $statusFilter = $request->status;
            if ($statusFilter === 'eligible') {
                $query->whereHas('trainerProfile', function ($q) {
                    // Profile 100% complete
                });
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $trainers = $query->paginate(15);

        // Institutional Overview Stats
        $allTrainers = User::where('role', 'trainer')->with('trainerProfile', 'trainerCertifications')->get();
        $totalTrainers = $allTrainers->count();
        $eligibleTrainers = $allTrainers->filter(fn ($t) => $t->trainerProfile && $t->trainerProfile->isComplete())->count();
        $certifiedTrainers = $allTrainers->filter(fn ($t) => $t->trainerCertifications->contains(fn ($c) => $c->calculated_status === 'certified'))->count();
        $expiredTrainers = $allTrainers->filter(fn ($t) => $t->trainerCertifications->contains(fn ($c) => $c->calculated_status === 'expired' || $c->calculated_status === 'renewal_required'))->count();

        $courses = Course::where('publish_status', 'published')->get();

        return view('dashboards.admin.trainer-certifications.index', compact(
            'trainers', 'totalTrainers', 'eligibleTrainers', 'certifiedTrainers', 'expiredTrainers', 'courses'
        ));
    }

    public function showTrainer(User $user)
    {
        if (! $user->isTrainer()) {
            abort(404);
        }

        $trainer = $user;
        $profile = $trainer->trainerProfile;
        $completionPercentage = $profile ? $profile->completion_percentage : 0;
        $isEligible = $profile ? $profile->isComplete() : false;

        $certifications = TrainerCertification::where('user_id', $trainer->id)
            ->with(['course', 'attempts'])
            ->get();

        $attempts = TrainerCertificationAttempt::whereHas('certification', function ($q) use ($trainer) {
            $q->where('user_id', $trainer->id);
        })
            ->with(['certification.course'])
            ->latest()
            ->get();

        $courses = Course::where('publish_status', 'published')->get();

        return view('dashboards.admin.trainer-certifications.show', compact(
            'trainer', 'profile', 'completionPercentage', 'isEligible', 'certifications', 'attempts', 'courses'
        ));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'assessment_id' => 'nullable|exists:assessments,id',
            'validity_years' => 'nullable|integer|min:1|max:5',
        ]);

        $trainer = User::findOrFail($request->trainer_id);
        if (! $trainer->isTrainer()) {
            return back()->with('error', 'Selected user is not a trainer.');
        }

        $profile = $trainer->trainerProfile;
        if (! $profile || ! $profile->isComplete()) {
            return back()->with('error', 'Trainer profile is incomplete (<100%). Complete profile before assigning certification.');
        }

        $course = Course::findOrFail($request->course_id);

        $cert = TrainerCertification::updateOrCreate(
            [
                'user_id' => $trainer->id,
                'course_id' => $course->id,
            ],
            [
                'assessment_id' => $request->assessment_id,
                'status' => 'assigned',
                'passing_score' => 80,
                'validity_years' => $request->validity_years ?? 1,
            ]
        );

        // Create Database Notification for Trainer
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\CertificationAssignedNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $trainer->id,
            'data' => json_encode([
                'title' => 'Certification Test Assigned',
                'message' => 'Admin has assigned you a certification assessment for "'.$course->title.'".',
                'link' => route('trainer.certifications.index'),
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        AuditLog::create([
            'user_id' => auth()->id() ?? $trainer->id,
            'action' => 'TRAINER_CERTIFICATION_ASSIGNED',
            'entity_type' => 'TrainerCertification',
            'entity_id' => $cert->id,
            'new_values' => ['trainer_email' => $trainer->email, 'course_title' => $course->title],
        ]);

        return back()->with('success', 'Certification assessment successfully assigned to trainer.');
    }
}
