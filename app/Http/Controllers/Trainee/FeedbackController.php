<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        // Allow trainees to provide feedback for all published courses
        $allCourses = Course::where('publish_status', 'published')->get();

        if ($allCourses->isEmpty()) {
            $allCourses = Course::all();
        }

        $feedbacks = Feedback::where('user_id', Auth::id())->with('course')->get();
        $reviewedCourseIds = $feedbacks->pluck('course_id')->toArray();

        $eligibleCourses = $allCourses->whereNotIn('id', $reviewedCourseIds);

        return view('dashboards.trainee.feedback.index', compact('eligibleCourses', 'feedbacks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'course_rating' => 'required|integer|min:1|max:5',
            'trainer_rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string|max:1000',
            'suggestions' => 'nullable|string|max:1000',
        ]);

        $hasReviewed = Feedback::where('user_id', Auth::id())->where('course_id', $request->course_id)->exists();

        if ($hasReviewed) {
            return back()->with('error', 'You have already submitted feedback for this course.');
        }

        Feedback::create([
            'user_id' => Auth::id(),
            'course_id' => $request->course_id,
            'course_rating' => $request->course_rating,
            'trainer_rating' => $request->trainer_rating,
            'comments' => $request->comments,
            'suggestions' => $request->suggestions,
            'status' => 'pending', // Default status
        ]);

        return back()->with('success', 'Thank you! Your feedback has been submitted for review.');
    }
}
