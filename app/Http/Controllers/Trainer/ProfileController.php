<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\TrainerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->trainerProfile ?? TrainerProfile::firstOrCreate(['user_id' => $user->id]);
        $competencies = $user->competencies()->get();

        return view('dashboards.trainer.profile', compact('user', 'profile', 'competencies'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Construct validation rules dynamically
        $rules = [
            'name' => 'required|string|max:255',
            'photo_base64' => 'nullable|string',
            'qualification' => 'nullable|string|max:1000',
            'experience' => 'nullable|string|max:5000',
            'expertise' => 'nullable|string|max:1000',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:5000',
            'subjects' => 'nullable|string|max:1000',
        ];

        // Only validate raw file upload if base64 is NOT provided
        if (! $request->filled('photo_base64') && $request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            if (! $photoFile->isValid() && ($photoFile->getError() === UPLOAD_ERR_INI_SIZE || $photoFile->getError() === UPLOAD_ERR_FORM_SIZE)) {
                return back()->withErrors(['photo' => 'The uploaded photo file exceeds the server 2MB limit. Please select a smaller file.'])->withInput();
            }
            $rules['photo'] = 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120';
        }

        $request->validate($rules);

        $user->update(['name' => $request->name]);

        $profileData = [
            'qualification' => $request->qualification,
            'experience' => $request->experience,
            'expertise' => $request->expertise,
            'department' => $request->department,
            'bio' => $request->bio,
            'subjects' => $request->subjects,
        ];

        // 1. Process Base64 Compressed Image if present (Bypasses PHP file upload limits)
        if ($request->filled('photo_base64')) {
            $base64Image = $request->photo_base64;
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                $data = substr($base64Image, strpos($base64Image, ',') + 1);
                $ext = strtolower($type[1]);
                if ($ext === 'jpeg') {
                    $ext = 'jpg';
                }
                $data = base64_decode($data);
                if ($data !== false) {
                    $filename = time().'_'.uniqid().'.'.$ext;
                    Storage::disk('public')->put('trainer_photos/'.$filename, $data);
                    $profileData['photo'] = Storage::url('trainer_photos/'.$filename);
                }
            }
        }
        // 2. Process Standard File Upload
        elseif ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file = $request->file('photo');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('trainer_photos', $filename, 'public');
            $profileData['photo'] = Storage::url($path);
        }

        TrainerProfile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return back()->with('success', 'Profile updated successfully!');
    }
}
