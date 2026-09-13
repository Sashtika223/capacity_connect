<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Competency;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\LearningResource;
use App\Models\User;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->input('q', ''));
        $category = $request->input('category');
        $department = $request->input('department');
        $difficulty = $request->input('difficulty');
        $status = $request->input('status');

        $courses = collect();
        $trainers = collect();
        $competencies = collect();
        $resources = collect();
        $announcements = collect();

        if (! empty($q) || $category || $department || $difficulty || $status) {
            // Courses search with filters
            $courseQuery = Course::with(['category', 'trainer', 'competencies']);

            if (! empty($q)) {
                $courseQuery->where(function ($query) use ($q) {
                    $query->where('title', 'LIKE', "%{$q}%")
                        ->orWhere('description', 'LIKE', "%{$q}%");
                });
            }

            if ($category) {
                $courseQuery->where('category_id', $category);
            }

            if ($department) {
                $courseQuery->where('department', $department);
            }

            if ($difficulty) {
                $courseQuery->where('difficulty', $difficulty);
            }

            if ($status) {
                $courseQuery->where('publish_status', $status);
            } else {
                $courseQuery->where('publish_status', 'published');
            }

            $courses = $courseQuery->get();

            // Trainers search
            if (! empty($q)) {
                $trainers = User::where('role', 'trainer')
                    ->where(function ($query) use ($q) {
                        $query->where('name', 'LIKE', "%{$q}%")
                            ->orWhere('department', 'LIKE', "%{$q}%")
                            ->orWhere('designation', 'LIKE', "%{$q}%")
                            ->orWhere('skills', 'LIKE', "%{$q}%");
                    })->get();

                // Competencies search
                $competencies = Competency::where('name', 'LIKE', "%{$q}%")
                    ->orWhere('description', 'LIKE', "%{$q}%")
                    ->get();

                // Learning Resources search
                $resources = LearningResource::where('title', 'LIKE', "%{$q}%")
                    ->orWhere('type', 'LIKE', "%{$q}%")
                    ->get();

                // Announcements search
                $announcements = Announcement::where('title', 'LIKE', "%{$q}%")
                    ->orWhere('content', 'LIKE', "%{$q}%")
                    ->get();
            }
        }

        $categories = CourseCategory::orderBy('name')->get();

        return view('search.index', compact(
            'q', 'category', 'department', 'difficulty', 'status',
            'courses', 'trainers', 'competencies', 'resources', 'announcements', 'categories'
        ));
    }
}
