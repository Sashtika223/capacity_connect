<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\AiContentDraft;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Total Courses
        $myCourses = $user->trainerCourses()->with('category')->get();
        if ($myCourses->isEmpty()) {
            $myCourses = Course::all();
        }
        $totalCourses = $myCourses->count();

        // 2. Total Trainees in own courses
        $courseIds = $myCourses->pluck('id');
        $totalTrainees = Enrollment::whereIn('course_id', $courseIds)->count();

        // 3. Course Completion Rate
        $completedEnrollments = Enrollment::whereIn('course_id', $courseIds)->where('status', 'completed')->count();
        $completionRate = $totalTrainees > 0 ? round(($completedEnrollments / $totalTrainees) * 100) : 0;

        // 4. Average Trainee Score
        $assessmentIds = Assessment::whereIn('course_id', $courseIds)->pluck('id');
        $averageScore = round(AssessmentAttempt::whereIn('assessment_id', $assessmentIds)->where('status', 'completed')->avg('percentage') ?? 0);

        // 5. Pending Evaluations (AI Drafts awaiting review)
        $pendingEvaluations = AiContentDraft::where('user_id', $user->id)->where('status', 'drafted')->count();

        // 6. Upcoming Assessments
        $upcomingAssessments = Assessment::whereIn('course_id', $courseIds)->latest()->take(4)->get();

        // 7. Learners Needing Attention (stuck enrollees or low scores)
        $learnersNeedingAttention = Enrollment::whereIn('course_id', $courseIds)
            ->where('status', 'enrolled')
            ->where('progress', '<', 30)
            ->with(['user', 'course'])
            ->take(5)
            ->get();

        return view('dashboards.trainer', compact(
            'myCourses',
            'totalCourses',
            'totalTrainees',
            'completionRate',
            'averageScore',
            'pendingEvaluations',
            'upcomingAssessments',
            'learnersNeedingAttention'
        ));
    }
}
