<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competency;
use Illuminate\Http\Request;

class CompetencyController extends Controller
{
    public function index()
    {
        $competencies = Competency::orderBy('name')->paginate(15);

        return view('dashboards.admin.competencies.index', compact('competencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:competencies',
            'type' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        Competency::create($validated);

        return back()->with('success', 'Competency created successfully.');
    }

    public function update(Request $request, Competency $competency)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:competencies,name,'.$competency->id,
            'type' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $competency->update($validated);

        return back()->with('success', 'Competency updated successfully.');
    }

    public function destroy(Competency $competency)
    {
        $competency->delete();

        return back()->with('success', 'Competency deleted successfully.');
    }
}
