<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentOption;
use App\Models\AssessmentQuestion;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function index()
    {
        $courses = Auth::user()->trainerCourses;
        if ($courses->isEmpty()) {
            $courses = Course::all();
        }
        $assessments = Assessment::whereIn('course_id', $courses->pluck('id'))->with('course', 'questions')->get();

        return view('dashboards.trainer.assessments.index', compact('assessments'));
    }

    public function create()
    {
        $courses = Auth::user()->trainerCourses;
        if ($courses->isEmpty()) {
            $courses = Course::all();
        }

        return view('dashboards.trainer.assessments.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:1|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'negative_marking' => 'nullable|boolean',
            'negative_mark_value' => 'nullable|numeric|min:0',
            'max_attempts' => 'nullable|integer|min:0',
            'randomize_questions' => 'nullable|boolean',
        ]);

        $course = Course::findOrFail($request->course_id);

        $validated['negative_marking'] = $request->has('negative_marking');
        $validated['randomize_questions'] = $request->has('randomize_questions');
        $validated['negative_mark_value'] = $request->input('negative_mark_value', 0.25);
        $validated['max_attempts'] = $request->input('max_attempts', 0);

        $assessment = Assessment::create($validated);

        return redirect()->route('trainer.assessments.edit', $assessment->id)->with('success', 'Assessment created successfully. You can now add questions.');
    }

    public function edit(Assessment $assessment)
    {
        $assessment->load('questions.options');

        return view('dashboards.trainer.assessments.edit', compact('assessment'));
    }

    public function update(Request $request, Assessment $assessment)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:1|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:draft,published',
            'negative_marking' => 'nullable|boolean',
            'negative_mark_value' => 'nullable|numeric|min:0',
            'max_attempts' => 'nullable|integer|min:0',
            'randomize_questions' => 'nullable|boolean',
        ]);

        $validated['negative_marking'] = $request->has('negative_marking');
        $validated['randomize_questions'] = $request->has('randomize_questions');
        $validated['negative_mark_value'] = $request->input('negative_mark_value', 0.25);
        $validated['max_attempts'] = $request->input('max_attempts', 0);

        $assessment->update($validated);

        return back()->with('success', 'Assessment updated successfully.');
    }

    public function generateQuickAssessment(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course = Course::findOrFail($request->course_id);

        $assessment = Assessment::updateOrCreate(
            [
                'course_id' => $course->id,
                'title' => '15-Minute Operational Assessment: '.$course->title,
            ],
            [
                'subject' => 'Emergency Management & Field Operations',
                'instructions' => 'Complete all 15 questions within the strict 15-minute countdown timer. Passing threshold is 70%.',
                'duration' => 15,
                'passing_score' => 70,
                'status' => 'published',
                'negative_marking' => false,
                'max_attempts' => 5,
                'randomize_questions' => false,
                'start_date' => now(),
            ]
        );

        $assessment->questions()->delete();

        $questionsData = [
            ['q' => 'What is the primary objective of a Category 4 Cyclone evacuation warning?', 'opts' => [['Immediate movement of vulnerable population to designated cyclone shelters', true], ['Storing extra emergency rations at home', false], ['Securing residential property windows', false], ['Awaiting social media updates', false]]],
            ['q' => 'Which meteorological sensor system provides real-time Doppler velocity tracking for tropical cyclones?', 'opts' => [['S-Band Doppler Weather Radar (DWR)', true], ['Barometric Pressure Gauge', false], ['Anemometer Mast', false], ['Hydrological Flow Meter', false]]],
            ['q' => 'What is the standard time limit for this emergency operational assessment?', 'opts' => [['15 Minutes', true], ['30 Minutes', false], ['45 Minutes', false], ['60 Minutes', false]]],
            ['q' => 'How far ahead of cyclone landfall should mandatory coastal evacuation orders be communicated?', 'opts' => [['24 to 36 Hours prior to landfall', true], ['2 to 4 Hours prior to landfall', false], ['72 Hours prior to landfall', false], ['1 Hour prior to landfall', false]]],
            ['q' => 'Which satellite frequency band is recommended for tactical backup communications during terrestrial network collapse?', 'opts' => [['C-Band and Ku-Band Emergency Satellite Terminals', true], ['FM Commercial Radio Frequency', false], ['Cellular 4G LTE Towers', false], ['Bluetooth Local Mesh', false]]],
            ['q' => 'What is the minimum recommended clean water allocation per person per day in emergency shelters?', 'opts' => [['15 Liters per day', true], ['2 Liters per day', false], ['50 Liters per day', false], ['1 Liter per day', false]]],
            ['q' => 'In the Incident Command System (ICS), who holds overall responsibility for incident safety and tactical deployment?', 'opts' => [['Incident Commander (IC)', true], ['Public Information Officer', false], ['Logistics Section Chief', false], ['Planning Section Chief', false]]],
            ['q' => 'What is the primary purpose of GIS spatial mapping during flood relief operations?', 'opts' => [['Identifying inundated zones and routing rescue boats to high-ground shelters', true], ['Calculating administrative budget expenses', false], ['Printing physical paper maps', false], ['Monitoring weather radar frequencies', false]]],
            ['q' => 'Which triage color code indicates immediate life-threatening injuries requiring priority emergency transport?', 'opts' => [['Red (Immediate Priority)', true], ['Yellow (Delayed Priority)', false], ['Green (Minimal Priority)', false], ['Black (Deceased / Expectant)', false]]],
            ['q' => 'What is the standard CPR compression-to-ventilation ratio for adult cardiac arrest victims?', 'opts' => [['30 Chest Compressions to 2 Rescue Breaths', true], ['15 Chest Compressions to 1 Rescue Breath', false], ['50 Chest Compressions to 5 Rescue Breaths', false], ['10 Chest Compressions to 2 Rescue Breaths', false]]],
            ['q' => 'Which document verifies trainer qualification and digital profile tier badges?', 'opts' => [['Digital Twin Trainer Profile Certificate', true], ['Temporary Attendance Sheet', false], ['Self-Assessment Questionnaire', false], ['Unverified Registration Form', false]]],
            ['q' => 'What is the minimum passing score percentage for the 15-minute emergency assessment?', 'opts' => [['70% Passing Grade', true], ['50% Passing Grade', false], ['80% Passing Grade', false], ['60% Passing Grade', false]]],
            ['q' => 'During storm surge conditions, what is the safest minimum elevation for emergency relief staging areas?', 'opts' => [['10 Meters above mean sea level', true], ['1 Meter above mean sea level', false], ['Mean Sea Level', false], ['2 Meters below sea level', false]]],
            ['q' => 'Which emergency broadcast frequency channel is designated for national disaster advisories?', 'opts' => [['Emergency Alert System (EAS) & All-Hazards Warning Network', true], ['Commercial Radio Music Channel', false], ['Local High-Frequency Band', false], ['Private Mesh Network', false]]],
            ['q' => 'What action is taken automatically when the 15-minute assessment timer reaches 00:00?', 'opts' => [['Automatic form submission and instant score calculation', true], ['Assessment reset and restart', false], ['Extra 10 minutes granted automatically', false], ['Cancellation of assessment attempt', false]]],
        ];

        foreach ($questionsData as $item) {
            $q = AssessmentQuestion::create([
                'assessment_id' => $assessment->id,
                'question_text' => $item['q'],
                'marks' => 10,
            ]);
            foreach ($item['opts'] as $opt) {
                AssessmentOption::create([
                    'assessment_question_id' => $q->id,
                    'option_text' => $opt[0],
                    'is_correct' => $opt[1],
                ]);
            }
        }

        return redirect()->route('trainer.assessments.edit', $assessment->id)
            ->with('success', 'Successfully generated & assigned 15-Question Assessment with 15-Minute Timer to trainees!');
    }
}
