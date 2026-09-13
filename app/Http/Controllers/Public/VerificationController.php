<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Certificate;

class VerificationController extends Controller
{
    public function verify($certificateId)
    {
        $certificate = Certificate::where('certificate_id', $certificateId)->with('course', 'user')->first();

        return view('public.verify', compact('certificate', 'certificateId'));
    }
}
