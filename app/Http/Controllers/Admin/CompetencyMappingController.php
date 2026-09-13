<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competency;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class CompetencyMappingController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['trainee', 'trainer'])->with('competencies')->get();
        $courses = Course::with('competencies')->get();
        $competencies = Competency::all();

        return view('dashboards.admin.competency-mapping.index', compact('users', 'courses', 'competencies'));
    }

    public function mapUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'competencies' => 'array',
            'competencies.*' => 'exists:competencies,id',
            'current_levels' => 'array',
            'required_levels' => 'array',
        ]);

        $user = User::findOrFail($validated['user_id']);

        $syncData = [];
        if (! empty($validated['competencies'])) {
            foreach ($validated['competencies'] as $compId) {
                $syncData[$compId] = [
                    'current_level' => $validated['current_levels'][$compId] ?? 0,
                    'required_level' => $validated['required_levels'][$compId] ?? 1,
                ];
            }
        }

        $user->competencies()->sync($syncData);

        return back()->with('success', 'User competencies mapped successfully.');
    }

    public function mapCourse(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'competencies' => 'array',
            'competencies.*' => 'exists:competencies,id',
            'levels' => 'array',
        ]);

        $course = Course::findOrFail($validated['course_id']);

        $syncData = [];
        if (! empty($validated['competencies'])) {
            foreach ($validated['competencies'] as $compId) {
                $syncData[$compId] = [
                    'level' => $validated['levels'][$compId] ?? 1,
                ];
            }
        }

        $course->competencies()->sync($syncData);

        return back()->with('success', 'Course competencies mapped successfully.');
    }
}
