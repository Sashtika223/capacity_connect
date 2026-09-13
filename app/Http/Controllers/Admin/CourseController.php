<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['category', 'trainer']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('course_code', 'like', "%{$search}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'published' || $request->status === 'draft') {
                $query->where('publish_status', $request->status);
            } elseif ($request->status === 'archived') {
                $query->where('status', 'inactive');
            }
        }

        $courses = $query->latest()->paginate(15)->withQueryString();
        $categories = CourseCategory::all();

        return view('dashboards.admin.courses.index', compact('courses', 'categories'));
    }

    public function create()
    {
        $categories = CourseCategory::all();
        $trainers = User::where('role', 'trainer')->where('status', 'active')->get();

        return view('dashboards.admin.courses.create', compact('categories', 'trainers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'course_code' => 'required|string|unique:courses',
            'category_id' => 'required|exists:course_categories,id',
            'trainer_id' => 'required|exists:users,id',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'description' => 'required|string',
            'publish_status' => 'required|in:draft,published',
        ]);

        $course = Course::create($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        $categories = CourseCategory::all();
        $trainers = User::where('role', 'trainer')->where('status', 'active')->get();

        return view('dashboards.admin.courses.edit', compact('course', 'categories', 'trainers'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'course_code' => 'required|string|unique:courses,course_code,'.$course->id,
            'category_id' => 'required|exists:course_categories,id',
            'trainer_id' => 'required|exists:users,id',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'description' => 'required|string',
            'publish_status' => 'required|in:draft,published',
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function toggleFeature(Course $course)
    {
        $course->update(['is_featured' => ! $course->is_featured]);

        return back()->with('success', 'Course feature status updated.');
    }

    public function toggleArchive(Course $course)
    {
        $newStatus = $course->status === 'active' ? 'inactive' : 'active';
        $course->update(['status' => $newStatus]);

        return back()->with('success', 'Course archive status updated.');
    }

    public function destroy(Course $course)
    {
        // Add cascading deletes or prevent deletion if enrolled
        if ($course->enrollments()->exists()) {
            return back()->with('error', 'Cannot delete course with active enrollments. Archive it instead.');
        }
        $course->delete();

        return back()->with('success', 'Course deleted permanently.');
    }
}
