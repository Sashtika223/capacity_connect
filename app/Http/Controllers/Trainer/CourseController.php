<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = auth()->user()->trainerCourses()->with('category')->get();

        return view('dashboards.trainer.courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = CourseCategory::all();

        return view('dashboards.trainer.courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'course_code' => 'required|string|unique:courses,course_code',
            'category_id' => 'required|exists:course_categories,id',
            'description' => 'required|string',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'nullable|string|max:255',
        ]);

        $course = new Course($request->all());
        $course->trainer_id = auth()->id();
        $course->save();

        return redirect()->route('trainer.courses.edit', $course->id)->with('success', 'Course created successfully. You can now add modules.');
    }

    public function edit(Course $course)
    {
        if ($course->trainer_id !== auth()->id()) {
            abort(403);
        }

        $categories = CourseCategory::all();
        $course->load('modules.lessons');

        return view('dashboards.trainer.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        if ($course->trainer_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:course_categories,id',
            'description' => 'required|string',
            'publish_status' => 'required|in:draft,published',
        ]);

        $course->update($request->all());

        return back()->with('success', 'Course updated successfully.');
    }

    public function enrollees(Course $course)
    {
        if ($course->trainer_id !== auth()->id()) {
            abort(403);
        }

        $enrollments = $course->enrollments()->with('user.traineeProfile')->get();

        return view('dashboards.trainer.courses.enrollees', compact('course', 'enrollments'));
    }
}
