<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;

class AssessmentStatsController extends Controller
{
    public function index()
    {
        $totalAssessments = Assessment::count();
        $publishedAssessments = Assessment::where('status', 'published')->count();

        $totalAttempts = AssessmentAttempt::count();
        $completedAttempts = AssessmentAttempt::where('status', 'completed')->count();

        $averageScore = AssessmentAttempt::where('status', 'completed')->avg('percentage') ?? 0;

        $passedAttempts = AssessmentAttempt::where('status', 'completed')->where('passed', true)->count();
        $passRate = $completedAttempts > 0 ? round(($passedAttempts / $completedAttempts) * 100) : 0;

        return view('dashboards.admin.assessments', compact(
            'totalAssessments', 'publishedAssessments',
            'totalAttempts', 'completedAttempts',
            'averageScore', 'passRate'
        ));
    }
}
