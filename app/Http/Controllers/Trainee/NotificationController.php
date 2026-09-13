<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\UserNotificationPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);

        return view('dashboards.trainee.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // If the notification has a URL, redirect to it
        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    public function preferences()
    {
        $user = Auth::user();
        $pref = $user->notificationPreference ?? UserNotificationPreference::firstOrCreate(['user_id' => $user->id]);

        return view('dashboards.trainee.notifications.preferences', compact('pref'));
    }

    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        $pref = $user->notificationPreference ?? UserNotificationPreference::firstOrCreate(['user_id' => $user->id]);

        $pref->update([
            'in_app_notifications' => $request->has('in_app_notifications'),
            'email_notifications' => $request->has('email_notifications'),
            'new_course_alerts' => $request->has('new_course_alerts'),
            'assessment_alerts' => $request->has('assessment_alerts'),
            'certificate_alerts' => $request->has('certificate_alerts'),
            'announcement_alerts' => $request->has('announcement_alerts'),
            'skill_gap_alerts' => $request->has('skill_gap_alerts'),
        ]);

        return back()->with('success', 'Notification preferences updated successfully.');
    }
}
