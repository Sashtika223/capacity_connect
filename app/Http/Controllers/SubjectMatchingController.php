<?php

namespace App\Http\Controllers;

use App\Models\Competency;
use App\Models\Course;
use App\Models\LearningResource;
use App\Models\PracticalAssessment;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectMatchingController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('query', ''));

        $matchedCompetencies = collect();
        $recommendedCourses = collect();
        $recommendedTrainers = collect();
        $recommendedResources = collect();
        $recommendedPracticalDrills = collect();

        if (! empty($query)) {
            // 1. Match Competencies
            $matchedCompetencies = Competency::where('name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->get();

            $compIds = $matchedCompetencies->pluck('id')->toArray();

            // 2. Match Courses
            $recommendedCourses = Course::where('publish_status', 'published')
                ->where(function ($q) use ($query, $compIds) {
                    $q->where('title', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%")
                        ->orWhereHas('competencies', function ($cq) use ($compIds) {
                            $cq->whereIn('competency_id', $compIds);
                        });
                })
                ->with(['trainer', 'category'])
                ->get();

            // 3. Match Expert Trainers
            $recommendedTrainers = User::whereIn('role', ['trainer', 'admin'])
                ->where(function ($q) use ($query, $compIds) {
                    $q->where('name', 'LIKE', "%{$query}%")
                        ->orWhere('department', 'LIKE', "%{$query}%")
                        ->orWhere('designation', 'LIKE', "%{$query}%")
                        ->orWhere('qualifications', 'LIKE', "%{$query}%")
                        ->orWhere('skills', 'LIKE', "%{$query}%")
                        ->orWhereHas('competencies', function ($cq) use ($compIds) {
                            $cq->whereIn('competency_id', $compIds);
                        });
                })
                ->with('competencies')
                ->get();

            // 4. Match Learning Resources
            $recommendedResources = LearningResource::where('title', 'LIKE', "%{$query}%")
                ->orWhere('type', 'LIKE', "%{$query}%")
                ->with('lesson.module.course')
                ->get();

            // 5. Match Practical Response Drills
            $recommendedPracticalDrills = PracticalAssessment::where('status', 'published')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->with('course')
                ->get();
        }

        return view('subject-matching.index', compact(
            'query',
            'matchedCompetencies',
            'recommendedCourses',
            'recommendedTrainers',
            'recommendedResources',
            'recommendedPracticalDrills'
        ));
    }
}
