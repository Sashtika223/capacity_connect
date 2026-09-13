<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Course;
use App\Services\GamificationService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(GamificationService $gamificationService)
    {
        $user = Auth::user();

        // Update daily streak
        $gamificationService->updateStreak($user);

        $enrolledCount = $user->enrollments()->where('status', 'enrolled')->count();
        $completedCount = $user->enrollments()->where('status', 'completed')->count();
        $certificatesCount = $user->certificates()->count();
        $averageScore = round($user->assessmentAttempts()->where('status', 'completed')->avg('percentage') ?? 0);

        $totalEnrollments = $enrolledCount + $completedCount;
        $learningProgress = $totalEnrollments > 0 ? round(($completedCount / $totalEnrollments) * 100) : 0;

        // Current Active Enrollments
        $currentEnrollments = $user->enrollments()
            ->where('status', 'enrolled')
            ->with('course.trainer')
            ->take(4)
            ->get();

        // Upcoming / Active Assessments
        $enrolledCourseIds = $user->enrollments()->pluck('course_id');
        $upcomingAssessments = Assessment::whereIn('course_id', $enrolledCourseIds)
            ->where('status', 'published')
            ->with('course')
            ->take(3)
            ->get();

        // Competency Skill Gaps
        $competencies = $user->competencies;
        $skillGaps = [];
        $gapCompIds = [];

        foreach ($competencies as $comp) {
            if ($comp->pivot->current_level < $comp->pivot->required_level) {
                $skillGaps[] = [
                    'competency' => $comp,
                    'current' => $comp->pivot->current_level,
                    'required' => $comp->pivot->required_level,
                    'gap' => $comp->pivot->required_level - $comp->pivot->current_level,
                ];
                $gapCompIds[] = $comp->id;
            }
        }

        // Recommended Courses based on gaps
        $recommendedCourses = Course::where('publish_status', 'published')
            ->whereNotIn('id', $enrolledCourseIds)
            ->whereHas('competencies', function ($q) use ($gapCompIds) {
                $q->whereIn('competency_id', $gapCompIds);
            })
            ->take(3)
            ->get();

        // If no gap recommendations, show featured published courses
        if ($recommendedCourses->isEmpty()) {
            $recommendedCourses = Course::where('publish_status', 'published')
                ->whereNotIn('id', $enrolledCourseIds)
                ->latest()
                ->take(3)
                ->get();
        }

        // User Badges & Gamification
        $badges = $user->badges()->get();
        $recentNotifications = $user->notifications()->take(5)->get();

        return view('dashboards.trainee.index', compact(
            'user',
            'enrolledCount',
            'completedCount',
            'certificatesCount',
            'averageScore',
            'learningProgress',
            'currentEnrollments',
            'upcomingAssessments',
            'skillGaps',
            'recommendedCourses',
            'badges',
            'recentNotifications'
        ));
    }
}
