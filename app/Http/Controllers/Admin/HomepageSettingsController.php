<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class HomepageSettingsController extends Controller
{
    public function index()
    {
        $setting = HomepageSetting::firstOrCreate([], [
            'hero_title' => 'Digital Capacity Building & Emergency Response Portal',
            'hero_description' => 'Institutional platform for workforce capacity assessment, disaster response drills, competency risk management, and verifiable professional certifications.',
        ]);

        return view('dashboards.admin.homepage-settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = HomepageSetting::firstOrFail();

        $validated = $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_description' => 'required|string',
            'important_notice' => 'nullable|string',
            'show_ai_section' => 'nullable|boolean',
            'show_disaster_section' => 'nullable|boolean',
            'show_offline_section' => 'nullable|boolean',
        ]);

        $oldValues = $setting->toArray();

        $setting->update([
            'hero_title' => $validated['hero_title'],
            'hero_description' => $validated['hero_description'],
            'important_notice' => $validated['important_notice'],
            'show_ai_section' => $request->has('show_ai_section'),
            'show_disaster_section' => $request->has('show_disaster_section'),
            'show_offline_section' => $request->has('show_offline_section'),
        ]);

        AuditLogService::log('Homepage Content Updated', $setting, $oldValues, $setting->toArray());

        return back()->with('success', 'Public homepage settings updated successfully.');
    }
}
