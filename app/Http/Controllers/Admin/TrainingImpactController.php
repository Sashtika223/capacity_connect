<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentAttempt;
use App\Models\Enrollment as CourseEnrollment;
use App\Models\User;

class TrainingImpactController extends Controller
{
    public function index()
    {
        // 1. Completion Rate & Training Participation
        $totalTrainees = User::where('role', 'trainee')->count();
        $activeTrainees = CourseEnrollment::distinct('user_id')->count('user_id');
        $participationRate = $totalTrainees > 0 ? ($activeTrainees / $totalTrainees) * 100 : 0;

        $totalEnrollments = CourseEnrollment::count();
        $completedEnrollments = CourseEnrollment::where('status', 'completed')->count();
        $completionRate = $totalEnrollments > 0 ? ($completedEnrollments / $totalEnrollments) * 100 : 0;

        // 2. Assessment Score Improvement (Before vs After)
        // We find users who took the same assessment > 1 time to calculate true lift.
        $multipleAttempts = AssessmentAttempt::select('user_id', 'assessment_id')
            ->groupBy('user_id', 'assessment_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        $totalLift = 0;
        $liftCount = 0;
        $traineeImprovements = [];

        foreach ($multipleAttempts as $ma) {
            $attempts = AssessmentAttempt::where('user_id', $ma->user_id)
                ->where('assessment_id', $ma->assessment_id)
                ->orderBy('created_at', 'asc')
                ->get();

            $firstScore = $attempts->first()->score_percentage;
            $lastScore = $attempts->last()->score_percentage;
            $lift = $lastScore - $firstScore;

            $totalLift += $lift;
            $liftCount++;

            // Track top improving trainees
            $user = User::find($ma->user_id);
            if ($user && $lift > 0) {
                if (! isset($traineeImprovements[$user->id])) {
                    $traineeImprovements[$user->id] = [
                        'user' => $user,
                        'total_lift' => 0,
                        'courses_completed' => 0,
                    ];
                }
                $traineeImprovements[$user->id]['total_lift'] += $lift;
            }
        }

        $avgScoreLift = $liftCount > 0 ? ($totalLift / $liftCount) : 0;

        // Add course completion data to improved trainees
        foreach ($traineeImprovements as $id => $data) {
            $traineeImprovements[$id]['courses_completed'] = CourseEnrollment::where('user_id', $id)
                ->where('status', 'completed')
                ->count();
        }

        // Sort improved trainees by highest lift
        usort($traineeImprovements, function ($a, $b) {
            return $b['total_lift'] <=> $a['total_lift'];
        });

        // 3. Competency Improvement Proxy
        // Trainees who have completed courses mapping to competencies they need
        $competencyLiftCount = 0;
        $allTrainees = User::where('role', 'trainee')->with('competencies')->get();
        foreach ($allTrainees as $trainee) {
            if ($trainee->competencies->count() > 0) {
                $completedCourses = CourseEnrollment::where('user_id', $trainee->id)
                    ->where('status', 'completed')
                    ->with('course.competencies')
                    ->get();

                $hasBenefited = false;
                foreach ($completedCourses as $enrollment) {
                    if ($enrollment->course) {
                        foreach ($enrollment->course->competencies as $courseComp) {
                            if ($trainee->competencies->contains('id', $courseComp->id)) {
                                $hasBenefited = true;
                                break;
                            }
                        }
                    }
                }
                if ($hasBenefited) {
                    $competencyLiftCount++;
                }
            }
        }
        $competencyLiftRate = $totalTrainees > 0 ? ($competencyLiftCount / $totalTrainees) * 100 : 0;

        // 4. Overall Training Effectiveness (OEE) Score
        // 40% Score Lift (normalized assuming max possible avg lift is around 40%)
        $normLift = min(100, ($avgScoreLift / 40) * 100);
        $oee = ($normLift * 0.4) + ($completionRate * 0.3) + ($participationRate * 0.3);

        return view('dashboards.admin.training-impact.index', compact(
            'participationRate',
            'completionRate',
            'avgScoreLift',
            'competencyLiftRate',
            'oee',
            'traineeImprovements'
        ));
    }
}
