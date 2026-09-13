<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with(['user', 'course'])->latest()->get();

        return view('dashboards.admin.feedback.index', compact('feedbacks'));
    }

    public function updateStatus(Request $request, Feedback $feedback)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $feedback->update(['status' => $request->status]);

        return back()->with('success', 'Feedback status updated to '.$request->status);
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return back()->with('success', 'Feedback permanently deleted.');
    }
}
