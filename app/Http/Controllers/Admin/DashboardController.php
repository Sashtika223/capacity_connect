<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiContentDraft;
use App\Models\AssessmentAttempt;
use App\Models\Certificate;
use App\Models\Competency;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Enterprise Metrics
        $totalUsers = User::count();
        $trainees = User::where('role', 'trainee')->count();
        $trainers = User::where('role', 'trainer')->count();

        $totalCourses = Course::count();
        $activeCourses = Course::where('publish_status', 'published')->count();

        $totalEnrollments = Enrollment::count();
        $completedCourses = Enrollment::where('status', 'completed')->count();
        $completionRate = $totalEnrollments > 0 ? round(($completedCourses / $totalEnrollments) * 100) : 0;

        $totalAssessments = AssessmentAttempt::count();
        $averageScore = round(AssessmentAttempt::where('status', 'completed')->avg('percentage') ?? 0);

        $totalCertificates = Certificate::count();
        $expiringCertificates = Certificate::where('cert_status', 'expiring_soon')->count();
        $pendingApprovals = AiContentDraft::where('status', 'drafted')->count() + Certificate::where('cert_status', 'renewal_required')->count();

        // 2. Competency Gaps & High Risk Competencies
        $competencies = Competency::with('users')->get();
        $competencyGapsCount = 0;
        $highRiskCompetenciesCount = 0;

        foreach ($competencies as $comp) {
            $req = 0;
            $cur = 0;
            foreach ($comp->users as $u) {
                $req += $u->pivot->required_level;
                $cur += $u->pivot->current_level;
            }
            if ($req > 0 && $cur < $req) {
                $competencyGapsCount++;
                if (($req - $cur) / $req > 0.3) {
                    $highRiskCompetenciesCount++;
                }
            }
        }

        // 3. Top Performing Courses & Top Trainers
        $topCourses = Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get();

        $topTrainers = User::where('role', 'trainer')
            ->withCount('trainerCourses')
            ->orderBy('trainer_courses_count', 'desc')
            ->take(5)
            ->get();

        // 4. Chart Data (Last 6 Months)
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->format('Y-m'));
        }

        $userGrowth = $months->map(fn ($m) => User::where('created_at', 'like', $m.'%')->count());
        $enrollmentChart = $months->map(fn ($m) => Enrollment::where('created_at', 'like', $m.'%')->count());
        $completionChart = $months->map(fn ($m) => Enrollment::where('status', 'completed')->where('updated_at', 'like', $m.'%')->count());
        $certificatesChart = $months->map(fn ($m) => Certificate::where('issue_date', 'like', $m.'%')->count());

        $passedAssessments = AssessmentAttempt::where('status', 'completed')->where('passed', true)->count();
        $failedAssessments = AssessmentAttempt::where('status', 'completed')->where('passed', false)->count();

        $pendingTrainerRequests = User::where('role', 'trainer')->where('trainer_status', 'pending')->count();
        $approvedTrainersCount = User::where('role', 'trainer')->where('trainer_status', 'approved')->count();
        $rejectedTrainerRequests = User::where('role', 'trainer')->where('trainer_status', 'rejected')->count();

        return view('dashboards.admin.index', compact(
            'totalUsers', 'trainees', 'trainers', 'totalCourses', 'activeCourses',
            'totalEnrollments', 'completedCourses', 'completionRate',
            'totalAssessments', 'averageScore', 'totalCertificates', 'expiringCertificates',
            'pendingApprovals', 'competencyGapsCount', 'highRiskCompetenciesCount',
            'pendingTrainerRequests', 'approvedTrainersCount', 'rejectedTrainerRequests',
            'topCourses', 'topTrainers',
            'months', 'userGrowth', 'enrollmentChart', 'completionChart',
            'certificatesChart', 'passedAssessments', 'failedAssessments'
        ));
    }
}
