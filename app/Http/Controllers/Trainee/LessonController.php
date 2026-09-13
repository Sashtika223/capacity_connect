<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Services\CourseCompletionService;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson)
    {
        $isEnrolled = Enrollment::where('user_id', auth()->id())->where('course_id', $course->id)->exists();
        if (! $isEnrolled) {
            abort(403, 'You must be enrolled to view this lesson.');
        }

        $course->load('modules.lessons');
        $isCompleted = LessonProgress::where('user_id', auth()->id())->where('lesson_id', $lesson->id)->exists();

        return view('dashboards.trainee.courses.lesson', compact('course', 'lesson', 'isCompleted'));
    }

    public function complete(Course $course, Lesson $lesson, CourseCompletionService $completionService)
    {
        $isEnrolled = Enrollment::where('user_id', auth()->id())->where('course_id', $course->id)->first();
        if (! $isEnrolled) {
            abort(403);
        }

        // Mark lesson complete
        LessonProgress::firstOrCreate([
            'user_id' => auth()->id(),
            'lesson_id' => $lesson->id,
        ], ['status' => 'completed']);

        // Calculate progress
        $totalLessons = 0;
        foreach ($course->modules as $module) {
            $totalLessons += $module->lessons()->count();
        }

        $completedLessons = LessonProgress::where('user_id', auth()->id())
            ->whereIn('lesson_id', function ($query) use ($course) {
                $query->select('lessons.id')
                    ->from('lessons')
                    ->join('course_modules', 'lessons.course_module_id', '=', 'course_modules.id')
                    ->where('course_modules.course_id', $course->id);
            })->count();

        $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
        $status = $progress == 100 ? 'completed' : 'enrolled';

        $isEnrolled->update([
            'progress' => $progress,
            'status' => $status,
        ]);

        $completionService->checkAndGenerateCertificate(auth()->user(), $course);

        return back()->with('success', 'Lesson marked as complete!');
    }
}
