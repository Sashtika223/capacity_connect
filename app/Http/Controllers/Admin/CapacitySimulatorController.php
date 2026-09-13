<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CapacitySimulation;
use App\Models\Competency;
use Illuminate\Http\Request;

class CapacitySimulatorController extends Controller
{
    public function index()
    {
        $competencies = Competency::all();
        $simulations = CapacitySimulation::with('competency')->latest()->get();

        return view('dashboards.admin.capacity-simulator.index', compact('competencies', 'simulations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'competency_id' => 'required|exists:competencies,id',
            'current_employees' => 'required|integer|min:0',
            'current_capacity' => 'required|integer|min:0',
            'required_capacity' => 'required|integer|min:1',
            'employees_to_train' => 'required|integer|min:0',
            'expected_improvement' => 'required|numeric|min:0.1',
            'training_duration_days' => 'nullable|integer|min:1',
            'training_sessions' => 'nullable|integer|min:1',
            'trainers_required' => 'nullable|integer|min:1',
        ]);

        $projected = $validated['current_capacity'] + ($validated['employees_to_train'] * $validated['expected_improvement']);
        $gap = max(0, $validated['required_capacity'] - $projected);
        $improvementPercentage = (($projected - $validated['current_capacity']) / $validated['required_capacity']) * 100;

        // Calculate derived fields
        $hoursPerSession = 8; // assumption: each session = 8 hrs
        $sessions = $validated['training_sessions'] ?? 1;
        $trainingHoursTotal = $validated['employees_to_train'] * $sessions * $hoursPerSession;

        // Risk reduction: proportional improvement vs gap
        $previousGap = max(0, $validated['required_capacity'] - $validated['current_capacity']);
        $riskReduction = $previousGap > 0 ? min(100, (($previousGap - $gap) / $previousGap) * 100) : 0;

        CapacitySimulation::create([
            'name' => $validated['name'],
            'competency_id' => $validated['competency_id'],
            'current_employees' => $validated['current_employees'],
            'current_capacity' => $validated['current_capacity'],
            'required_capacity' => $validated['required_capacity'],
            'employees_to_train' => $validated['employees_to_train'],
            'expected_improvement' => $validated['expected_improvement'],
            'projected_capacity' => $projected,
            'remaining_gap' => $gap,
            'improvement_percentage' => $improvementPercentage,
            'training_duration_days' => $validated['training_duration_days'] ?? null,
            'training_sessions' => $sessions,
            'trainers_required' => $validated['trainers_required'] ?? null,
            'training_hours_total' => $trainingHoursTotal,
            'risk_reduction_percentage' => $riskReduction,
        ]);

        return redirect()->back()->with('success', 'Simulation scenario saved successfully.');
    }

    public function destroy($id)
    {
        CapacitySimulation::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Simulation deleted.');
    }
}
