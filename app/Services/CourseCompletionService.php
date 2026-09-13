<?php

namespace App\Services;

use App\Models\AssessmentAttempt;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\LessonProgress;
use App\Models\User;
use App\Notifications\CertificateGeneratedNotification;
use App\Notifications\CourseCompletedNotification;
use Illuminate\Support\Str;

class CourseCompletionService
{
    /**
     * Check if a user has completed a course and generate a certificate if qualified.
     */
    public function checkAndGenerateCertificate(User $user, Course $course)
    {
        // 1. Check if already has certificate
        if (Certificate::where('user_id', $user->id)->where('course_id', $course->id)->exists()) {
            return false;
        }

        // 2. Check lesson progress (must have completed all lessons)
        $totalLessons = 0;
        foreach ($course->modules as $module) {
            $totalLessons += $module->lessons()->count();
        }

        if ($totalLessons > 0) {
            $completedLessons = LessonProgress::where('user_id', $user->id)
                ->whereHas('lesson.module', function ($q) use ($course) {
                    $q->where('course_id', $course->id);
                })
                ->where('is_completed', true)
                ->count();

            if ($completedLessons < $totalLessons) {
                return false; // Not all lessons completed
            }
        }

        // 3. Check mandatory assessments (must have passed all published assessments)
        $assessments = $course->assessments()->where('status', 'published')->get();
        $finalScore = null;

        if ($assessments->count() > 0) {
            $totalScore = 0;
            foreach ($assessments as $assessment) {
                $passedAttempt = AssessmentAttempt::where('user_id', $user->id)
                    ->where('assessment_id', $assessment->id)
                    ->where('status', 'completed')
                    ->where('passed', true)
                    ->orderByDesc('percentage')
                    ->first();

                if (! $passedAttempt) {
                    return false; // Found an assessment they haven't passed
                }

                $totalScore += $passedAttempt->percentage;
            }
            // Average score across assessments
            $finalScore = round($totalScore / $assessments->count());
        }

        // 4. All checks passed, generate certificate
        $certificateId = 'CC-'.strtoupper(Str::random(4)).'-'.strtoupper(Str::random(4)).'-'.date('Y');

        $certificate = Certificate::create([
            'certificate_id' => $certificateId,
            'user_id' => $user->id,
            'course_id' => $course->id,
            'score' => $finalScore,
            'issue_date' => now(),
        ]);

        // Trigger Notifications
        $user->notify(new CourseCompletedNotification($course));
        $user->notify(new CertificateGeneratedNotification($certificate));

        return true;
    }
}
