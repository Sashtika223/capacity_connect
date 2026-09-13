<?php

namespace App\Http\Controllers;

use App\Mail\TrainerRegistrationNotification;
use App\Models\TraineeProfile;
use App\Models\TrainerProfile;
use App\Models\User;
use App\Notifications\TrainerRegistrationRequestNotification;
use App\Services\HttpMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'admin' && strtolower($user->email) !== 'admin.capacity.connect.lms@gmail.com') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ])->onlyInput('email');
            }

            // Check Trainer Approval Status
            if ($user->isTrainer()) {
                if ($user->trainer_status === 'pending') {
                    Auth::logout();

                    return back()->withErrors([
                        'email' => 'Your Trainer registration request is still pending Admin approval. You will receive an email once your request has been reviewed.',
                    ])->onlyInput('email');
                }

                if ($user->trainer_status === 'rejected') {
                    Auth::logout();

                    return back()->withErrors([
                        'email' => 'Your Trainer registration request has been rejected by the Admin.',
                    ])->onlyInput('email');
                }
            }

            $request->session()->regenerate();

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:trainee,trainer'],
        ]);

        if ($request->role === 'trainer') {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'trainer',
                'trainer_status' => 'pending',
                'status' => 'inactive',
            ]);

            TrainerProfile::create([
                'user_id' => $user->id,
            ]);

            // 1. Real Email Notification to Admin (HTTPS API with SMTP/Log Fallback)
            $adminEmail = 'admin.capacity.connect.lms@gmail.com';
            HttpMailService::send($adminEmail, new TrainerRegistrationNotification($user));

            // 2. In-Portal Notification for Admin Users
            $admins = User::where('role', 'admin')->get();
            if ($admins->isEmpty()) {
                $admins = User::where('email', 'admin.capacity.connect.lms@gmail.com')->get();
            }

            foreach ($admins as $admin) {
                $admin->notify(new TrainerRegistrationRequestNotification($user));
            }

            return redirect()->route('login')->with('success', 'Trainer registration submitted successfully. Your request has been sent to the Admin for approval. You will receive an email once your request has been approved or rejected.');
        }

        // Trainee Registration
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'trainee',
            'status' => 'active',
        ]);

        TraineeProfile::create([
            'user_id' => $user->id,
        ]);

        Auth::login($user);

        return $this->redirectBasedOnRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function redirectBasedOnRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isTrainer()) {
            $profile = $user->trainerProfile;
            if (! $profile || ! $profile->isComplete()) {
                return redirect()->route('trainer.profile')
                    ->with('warning', 'Complete your trainer profile to 100% to become eligible for certification.');
            }

            return redirect()->route('trainer.dashboard');
        } else {
            return redirect()->route('trainee.dashboard');
        }
    }
}
