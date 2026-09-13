<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CompetencyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $competencies = $user->competencies()->get();

        $gaps = [];
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
        }

        // Recommend courses that teach these missing competencies at a level >= required_level
        $recommendedCourses = Course::where('publish_status', 'published')
            ->whereHas('competencies', function ($query) use ($gapCompetencyIds) {
                $query->whereIn('competency_id', $gapCompetencyIds);
            })
            ->with(['competencies' => function ($query) use ($gapCompetencyIds) {
                $query->whereIn('competency_id', $gapCompetencyIds);
            }])
            ->get()
            ->filter(function ($course) use ($competencies) {
                // Course is valid if it teaches at least one gap competency at required level
                foreach ($course->competencies as $courseComp) {
                    $userComp = $competencies->where('id', $courseComp->id)->first();
                    if ($userComp && $courseComp->pivot->level >= $userComp->pivot->required_level) {
                        return true;
                    }
                }

                return false;
            });

        $levelLabels = [0 => 'None', 1 => 'Beginner', 2 => 'Intermediate', 3 => 'Advanced', 4 => 'Expert'];

        return view('dashboards.trainee.competencies.index', compact('competencies', 'gaps', 'recommendedCourses', 'levelLabels'));
    }
}
