<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\TraineeProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->traineeProfile ?? new TraineeProfile;

        return view('dashboards.trainee.profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'qualifications' => 'nullable|string',
            'work_experience' => 'nullable|string',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'interests' => 'nullable|string',
            'skills' => 'nullable|string',
            'certificates_list' => 'nullable|string',
        ]);

        $user->update(['name' => $request->name]);

        TraineeProfile::updateOrCreate(
            ['user_id' => $user->id],
            $request->only([
                'phone', 'qualifications', 'work_experience',
                'designation', 'department', 'interests',
                'skills', 'certificates_list',
            ])
        );

        return back()->with('status', 'Profile updated successfully.');
    }
}
