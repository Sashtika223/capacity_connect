<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\PracticalAssessment;
use App\Models\PracticalAttempt;
use App\Models\PracticalAttemptLog;
use App\Models\PracticalNode;
use App\Models\PracticalOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PracticalAssessmentController extends Controller
{
    public function index()
    {
        $assessments = PracticalAssessment::where('status', 'published')
            ->with(['course', 'nodes'])
            ->latest()
            ->paginate(9);

        $myAttempts = PracticalAttempt::where('user_id', Auth::id())
            ->with('assessment')
            ->latest()
            ->get();

        return view('trainee.practical.index', compact('assessments', 'myAttempts'));
    }

    public function start(PracticalAssessment $practical)
    {
        // Check for existing in-progress attempt
        $attempt = PracticalAttempt::where('practical_assessment_id', $practical->id)
            ->where('user_id', Auth::id())
            ->where('status', 'in-progress')
            ->first();

        if (! $attempt) {
            $attempt = PracticalAttempt::create([
                'practical_assessment_id' => $practical->id,
                'user_id' => Auth::id(),
                'final_score' => 0,
                'status' => 'in-progress',
                'start_time' => now(),
            ]);

            // Find start node
            $startNode = $practical->startNode ?? $practical->nodes()->first();

            if ($startNode) {
                PracticalAttemptLog::create([
                    'practical_attempt_id' => $attempt->id,
                    'node_id' => $startNode->id,
                    'option_id' => null,
                    'score_delta' => 0,
                    'consequence_text' => 'Simulation Started.',
                ]);
            }
        }

        return redirect()->route('trainee.practical.play', $attempt);
    }

    public function play(PracticalAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        if ($attempt->status === 'completed') {
            return redirect()->route('trainee.practical.result', $attempt);
        }

        $attempt->load(['assessment', 'logs.node', 'logs.option']);
        $lastLog = $attempt->logs()->latest('id')->first();

        if (! $lastLog || ! $lastLog->node) {
            return redirect()->route('trainee.practical.index')->with('error', 'Simulation node not found.');
        }

        $currentNode = PracticalNode::with('options')->find($lastLog->node_id);

        return view('trainee.practical.play', compact('attempt', 'currentNode', 'lastLog'));
    }

    public function choose(Request $request, PracticalAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'option_id' => 'required|exists:practical_options,id',
        ]);

        $option = PracticalOption::findOrFail($validated['option_id']);

        // Update attempt score
        $newScore = ($attempt->final_score ?? 0) + $option->score_delta;
        $attempt->update(['final_score' => $newScore]);

        // Next node check
        if ($option->next_node_id) {
            PracticalAttemptLog::create([
                'practical_attempt_id' => $attempt->id,
                'node_id' => $option->next_node_id,
                'option_id' => $option->id,
                'score_delta' => $option->score_delta,
                'consequence_text' => $option->consequence_text ?? $option->feedback,
            ]);

            return redirect()->route('trainee.practical.play', $attempt);
        } else {
            // End of simulation path
            $attempt->update([
                'status' => 'completed',
                'end_time' => now(),
            ]);

            PracticalAttemptLog::create([
                'practical_attempt_id' => $attempt->id,
                'node_id' => $option->practical_node_id,
                'option_id' => $option->id,
                'score_delta' => $option->score_delta,
                'consequence_text' => ($option->consequence_text ? $option->consequence_text.' ' : '').'Simulation Concluded.',
            ]);

            return redirect()->route('trainee.practical.result', $attempt);
        }
    }

    public function result(PracticalAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'trainer') {
            abort(403);
        }

        $attempt->load(['assessment', 'logs.node', 'logs.option']);

        return view('trainee.practical.result', compact('attempt'));
    }
}
