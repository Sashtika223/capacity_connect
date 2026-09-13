<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::where('user_id', Auth::id())->with('course')->latest()->get();

        return view('dashboards.trainee.certificates.index', compact('certificates'));
    }

    public function show($id)
    {
        $certificate = Certificate::where('user_id', Auth::id())->where('id', $id)->with('course', 'user')->firstOrFail();

        return view('dashboards.trainee.certificates.show', compact('certificate'));
    }

    public function download($id)
    {
        $certificate = Certificate::where('user_id', Auth::id())->where('id', $id)->with('course', 'user')->firstOrFail();

        return view('dashboards.trainee.certificates.download', compact('certificate'));
    }

    public function uploadRenewal(Request $request, $id)
    {
        $certificate = Certificate::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        $request->validate(['renewal_file' => 'required|file|mimes:pdf,jpg,png|max:5120']);

        $path = $request->file('renewal_file')->store('certificate_renewals', 'public');
        $certificate->update([
            'renewal_file_path' => $path,
            'renewal_verified' => false,
            'cert_status' => 'renewal_required',
        ]);

        return redirect()->back()->with('success', 'Renewal document uploaded. An administrator will verify it shortly.');
    }
}
