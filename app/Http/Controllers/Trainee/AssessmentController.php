<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\AssessmentAttempt;
use App\Services\CourseCompletionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $enrolledCourseIds = $user->enrollments()->pluck('course_id');

        $assessments = Assessment::whereIn('course_id', $enrolledCourseIds)
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->with(['course', 'attempts' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->get();

        return view('dashboards.trainee.assessments.index', compact('assessments'));
    }

    public function show(Assessment $assessment)
    {
        $user = Auth::user();

        // Ensure enrolled
        if (! $user->enrollments()->where('course_id', $assessment->course_id)->exists()) {
            abort(403);
        }

        // Ensure within timeframe
        if ($assessment->start_date && $assessment->start_date > now()) {
            abort(403, 'Assessment has not started.');
        }
        if ($assessment->end_date && $assessment->end_date < now()) {
            abort(403, 'Assessment deadline has passed.');
        }

        // Check active/in-progress attempt
        $attempt = AssessmentAttempt::where('user_id', $user->id)
            ->where('assessment_id', $assessment->id)
            ->where('status', 'in-progress')
            ->first();

        // Check completed attempts
        $completedAttemptsCount = AssessmentAttempt::where('user_id', $user->id)
            ->where('assessment_id', $assessment->id)
            ->where('status', 'completed')
            ->count();

        if (! $attempt) {
            if ($assessment->max_attempts > 0 && $completedAttemptsCount >= $assessment->max_attempts) {
                $lastAttempt = AssessmentAttempt::where('user_id', $user->id)
                    ->where('assessment_id', $assessment->id)
                    ->latest()
                    ->first();

                return redirect()->route('trainee.assessments.result', $lastAttempt->id)
                    ->with('error', 'You have reached the maximum allowed attempts ('.$assessment->max_attempts.') for this assessment.');
            }

            $attempt = AssessmentAttempt::create([
                'user_id' => $user->id,
                'assessment_id' => $assessment->id,
                'start_time' => now(),
                'status' => 'in-progress',
            ]);
        }

        $assessment->load('questions.options');

        if ($assessment->randomize_questions) {
            $assessment->setRelation('questions', $assessment->questions->shuffle());
        }

        return view('dashboards.trainee.assessments.show', compact('assessment', 'attempt'));
    }

    public function submit(Request $request, Assessment $assessment)
    {
        $user = Auth::user();
        $attempt = AssessmentAttempt::where('user_id', $user->id)
            ->where('assessment_id', $assessment->id)
            ->where('status', 'in-progress')
            ->firstOrFail();

        // Server-side validation: Check deadline
        if ($assessment->end_date && $assessment->end_date < now()) {
            return $this->finalizeAttempt($attempt, $assessment, []); // Submit empty if past deadline
        }

        // Server-side validation: Check duration (with 2 min grace period for network latency)
        $elapsedMinutes = now()->diffInMinutes($attempt->start_time);
        if ($elapsedMinutes > ($assessment->duration + 2)) {
            return $this->finalizeAttempt($attempt, $assessment, []); // Timeout
        }

        $answers = $request->input('answers', []);

        return $this->finalizeAttempt($attempt, $assessment, $answers);
    }

    private function finalizeAttempt($attempt, $assessment, $submittedAnswers)
    {
        $totalScore = 0;
        $maxScore = 0;

        foreach ($assessment->questions as $question) {
            $maxScore += $question->marks;
            $selectedOptionId = $submittedAnswers[$question->id] ?? null;
            $isCorrect = false;

            if ($selectedOptionId) {
                $option = $question->options()->where('id', $selectedOptionId)->first();
                if ($option && $option->is_correct) {
                    $isCorrect = true;
                    $totalScore += $question->marks;
                } else {
                    // Negative marking logic if enabled
                    if ($assessment->negative_marking) {
                        $penalty = $assessment->negative_mark_value ?? 0.25;
                        $totalScore -= $penalty;
                    }
                }
            }

            AssessmentAnswer::updateOrCreate(
                ['assessment_attempt_id' => $attempt->id, 'assessment_question_id' => $question->id],
                ['assessment_option_id' => $selectedOptionId, 'is_correct' => $isCorrect]
            );
        }

        // Prevent negative total score if preferred
        $totalScore = max(0, $totalScore);

        $percentage = $maxScore > 0 ? round(($totalScore / $maxScore) * 100) : 0;
        $passed = $percentage >= $assessment->passing_score;

        $attempt->update([
            'end_time' => now(),
            'status' => 'completed',
            'score' => $totalScore,
            'percentage' => $percentage,
            'passed' => $passed,
        ]);

        // Check if certificate should be generated
        $completionService = app(CourseCompletionService::class);
        $completionService->checkAndGenerateCertificate(Auth::user(), $assessment->course);

        return redirect()->route('trainee.assessments.result', $attempt->id)->with('success', 'Assessment submitted successfully.');
    }

    public function result(AssessmentAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'trainer') {
            abort(403);
        }
        $attempt->load('assessment.course', 'answers.question.options');

        return view('dashboards.trainee.assessments.result', compact('attempt'));
    }

    public function results()
    {
        $user = Auth::user();

        $attempts = AssessmentAttempt::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with(['assessment.course'])
            ->latest()
            ->get();

        $totalAttempts = $attempts->count();
        $passedAttempts = $attempts->where('passed', true)->count();
        $avgScore = $totalAttempts > 0 ? round($attempts->avg('percentage')) : 0;

        return view('dashboards.trainee.assessments.results', compact('attempts', 'totalAttempts', 'passedAttempts', 'avgScore'));
    }
}
