<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Notifications\CourseEnrolledNotification;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('publish_status', 'published')->with('category', 'trainer')->get();
        $enrolledCourseIds = auth()->check() ? Enrollment::where('user_id', auth()->id())->pluck('course_id')->toArray() : [];

        return view('dashboards.trainee.courses.index', compact('courses', 'enrolledCourseIds'));
    }

    public function show(Course $course)
    {
        if ($course->publish_status !== 'published') {
            abort(404);
        }

        $course->load(['modules.lessons', 'feedback' => function ($query) {
            $query->where('status', 'approved')->with('user');
        }]);
        $isEnrolled = Enrollment::where('user_id', auth()->id())->where('course_id', $course->id)->exists();

        return view('dashboards.trainee.courses.show', compact('course', 'isEnrolled'));
    }

    public function enroll(Course $course)
    {
        if ($course->publish_status !== 'published') {
            abort(404);
        }

        $enrollment = Enrollment::firstOrCreate(
            ['user_id' => auth()->id(), 'course_id' => $course->id],
            ['status' => 'enrolled', 'progress' => 0]
        );

        if ($enrollment->wasRecentlyCreated) {
            auth()->user()->notify(new CourseEnrolledNotification($course));
        }

        return redirect()->route('trainee.courses.show', $course->id)->with('success', 'Successfully enrolled in course!');
    }
}
