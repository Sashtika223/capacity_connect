<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\AssessmentAttempt;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment as CourseEnrollment;
use Illuminate\Support\Facades\Auth;

class DigitalTwinController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Profile Data
        $profile = $user->traineeProfile;

        // 2. Competencies
        $competencies = $user->competencies()->get();

        $gaps = [];
        $strengths = [];
        $gapCompetencyIds = [];

        foreach ($competencies as $comp) {
            $current = $comp->pivot->current_level;
            $required = $comp->pivot->required_level;

            if ($current < $required) {
                $gaps[] = [
                    'competency' => $comp,
                    'gap' => $required - $current,
                ];
                $gapCompetencyIds[] = $comp->id;
            }

            if ($current >= $required && $current > 0) {
                $strengths[] = [
                    'competency' => $comp,
                    'margin' => $current - $required,
                ];
            }
        }

        // Sort strengths by margin and current level descending
        usort($strengths, function ($a, $b) {
            if ($a['margin'] === $b['margin']) {
                return $b['competency']->pivot->current_level <=> $a['competency']->pivot->current_level;
            }

            return $b['margin'] <=> $a['margin'];
        });

        // 3. Training Progress
        $enrollments = CourseEnrollment::where('user_id', $user->id)->with('course')->get();
        $completedCourses = $enrollments->where('status', 'completed');
        $activeCourses = $enrollments->where('status', 'in_progress');

        // Assessment Results
        $assessments = AssessmentAttempt::where('user_id', $user->id)
            ->with('assessment.course')
            ->orderBy('completed_at', 'desc')
            ->get();

        $averageScore = $assessments->count() > 0 ? round($assessments->avg('score_percentage'), 1) : 0;

        // 4. Certificates
        $certificates = Certificate::where('user_id', $user->id)->with('course')->orderBy('issued_date', 'desc')->get();

        // 5. Recommended Learning
        $recommendedCourses = Course::where('publish_status', 'published')
            ->whereHas('competencies', function ($query) use ($gapCompetencyIds) {
                $query->whereIn('competency_id', $gapCompetencyIds);
            })
            ->with(['competencies' => function ($query) use ($gapCompetencyIds) {
                $query->whereIn('competency_id', $gapCompetencyIds);
            }])
            ->get()
            ->filter(function ($course) use ($competencies) {
                foreach ($course->competencies as $courseComp) {
                    $userComp = $competencies->where('id', $courseComp->id)->first();
                    if ($userComp && $courseComp->pivot->level >= $userComp->pivot->required_level) {
                        return true;
                    }
                }

                return false;
            })
            ->take(3); // Limit to top 3 recommendations

        $levelLabels = [0 => 'None', 1 => 'Beginner', 2 => 'Intermediate', 3 => 'Advanced', 4 => 'Expert'];

        return view('dashboards.trainee.digital-twin.index', compact(
            'user',
            'profile',
            'competencies',
            'strengths',
            'gaps',
            'completedCourses',
            'activeCourses',
            'assessments',
            'averageScore',
            'certificates',
            'recommendedCourses',
            'levelLabels'
        ));
    }
}
