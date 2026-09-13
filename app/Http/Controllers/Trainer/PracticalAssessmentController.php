<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\PracticalAssessment;
use App\Models\PracticalNode;
use App\Models\PracticalOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PracticalAssessmentController extends Controller
{
    public function index()
    {
        $assessments = PracticalAssessment::with(['course', 'creator', 'nodes'])
            ->latest()
            ->paginate(10);

        return view('trainer.practical.index', compact('assessments'));
    }

    public function create()
    {
        $courses = Course::orderBy('title')->get();

        return view('trainer.practical.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'nullable|exists:courses,id',
            'status' => 'required|in:draft,published',
        ]);

        $assessment = PracticalAssessment::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'course_id' => $validated['course_id'],
            'created_by' => Auth::id(),
            'status' => $validated['status'],
        ]);

        // Create default start node
        PracticalNode::create([
            'practical_assessment_id' => $assessment->id,
            'title' => 'Initial Scenario Situation',
            'situation_text' => 'Describe the initial situation or disaster event here...',
            'is_start' => true,
        ]);

        return redirect()->route('trainer.practical.show', $assessment)
            ->with('success', 'Practical assessment simulation created successfully. Add decision nodes below.');
    }

    public function show(PracticalAssessment $practical)
    {
        $practical->load(['nodes.options.nextNode', 'course']);

        return view('trainer.practical.show', compact('practical'));
    }

    public function edit(PracticalAssessment $practical)
    {
        $courses = Course::orderBy('title')->get();

        return view('trainer.practical.edit', compact('practical', 'courses'));
    }

    public function update(Request $request, PracticalAssessment $practical)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'nullable|exists:courses,id',
            'status' => 'required|in:draft,published',
        ]);

        $practical->update($validated);

        return redirect()->route('trainer.practical.show', $practical)
            ->with('success', 'Practical assessment updated successfully.');
    }

    public function destroy(PracticalAssessment $practical)
    {
        $practical->delete();

        return redirect()->route('trainer.practical.index')
            ->with('success', 'Practical assessment removed.');
    }

    public function storeNode(Request $request, PracticalAssessment $practical)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'situation_text' => 'required|string',
            'is_start' => 'nullable|boolean',
        ]);

        if (! empty($validated['is_start'])) {
            PracticalNode::where('practical_assessment_id', $practical->id)->update(['is_start' => false]);
        }

        PracticalNode::create([
            'practical_assessment_id' => $practical->id,
            'title' => $validated['title'],
            'situation_text' => $validated['situation_text'],
            'is_start' => ! empty($validated['is_start']),
        ]);

        return back()->with('success', 'Scenario step created.');
    }

    public function storeOption(Request $request, PracticalNode $node)
    {
        $validated = $request->validate([
            'option_text' => 'required|string',
            'next_node_id' => 'nullable|exists:practical_nodes,id',
            'consequence_text' => 'nullable|string',
            'score_delta' => 'required|integer',
            'feedback' => 'nullable|string',
        ]);

        PracticalOption::create([
            'practical_node_id' => $node->id,
            'option_text' => $validated['option_text'],
            'next_node_id' => $validated['next_node_id'],
            'consequence_text' => $validated['consequence_text'],
            'score_delta' => $validated['score_delta'],
            'feedback' => $validated['feedback'],
        ]);

        return back()->with('success', 'Action/Decision option added.');
    }

    public function deleteNode(PracticalNode $node)
    {
        $assessmentId = $node->practical_assessment_id;
        $node->delete();

        return back()->with('success', 'Scenario node removed.');
    }

    public function deleteOption(PracticalOption $option)
    {
        $option->delete();

        return back()->with('success', 'Action option removed.');
    }
}
