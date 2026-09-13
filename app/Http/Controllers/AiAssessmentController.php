<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentOption;
use App\Models\AssessmentQuestion;
use App\Models\Course;
use App\Models\Lesson;
use App\Services\GeminiAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiAssessmentController extends Controller
{
    /**
     * Generate an assessment using Gemini AI and import it into a course.
     */
    public function generateAssessment(Request $request, GeminiAiService $aiService)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'document' => 'nullable|file|mimes:pdf,txt,docx,doc|max:20480',
            'pasted_content' => 'nullable|string|max:50000',
            'difficulty' => 'required|in:easy,medium,hard',
            'num_questions' => 'nullable|integer|min:3|max:15',
        ]);

        $course = Course::findOrFail($request->course_id);
        $numQuestions = $request->input('num_questions', 5);
        $difficulty = $request->input('difficulty', 'medium');
        $extractedText = '';

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $ext = strtolower($file->getClientOriginalExtension());
            if ($ext === 'txt') {
                $extractedText = file_get_contents($file->getRealPath());
            } else {
                $extractedText = 'Reference Material from '.$file->getClientOriginalName();
            }
        } elseif ($request->filled('pasted_content')) {
            $extractedText = $request->pasted_content;
        } else {
            // Extract from course lessons
            $lessons = Lesson::whereHas('module', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })->get();

            $extractedText = $lessons->pluck('content')->implode("\n\n");
            if (empty($extractedText)) {
                $extractedText = $course->title."\n".$course->description;
            }
        }

        try {
            $aiResult = $aiService->generateAssessmentFromText(
                $extractedText,
                $course->title,
                $difficulty,
                $numQuestions
            );

            // Create or update Assessment for the course
            $assessment = Assessment::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'title' => $aiResult['title'] ?? ('AI Generated Assessment - '.$course->title),
                ],
                [
                    'subject' => $course->title,
                    'instructions' => $aiResult['instructions'] ?? 'Complete all questions carefully.',
                    'duration' => $aiResult['duration_minutes'] ?? 15,
                    'passing_score' => $aiResult['passing_score'] ?? 70,
                    'status' => 'published',
                    'negative_marking' => false,
                    'max_attempts' => 5,
                    'randomize_questions' => true,
                ]
            );

            // Add Questions & Options
            if (! empty($aiResult['questions'])) {
                foreach ($aiResult['questions'] as $qData) {
                    $question = AssessmentQuestion::create([
                        'assessment_id' => $assessment->id,
                        'question_text' => $qData['question_text'],
                        'marks' => $qData['marks'] ?? 10,
                    ]);

                    if (! empty($qData['options'])) {
                        foreach ($qData['options'] as $optData) {
                            AssessmentOption::create([
                                'assessment_question_id' => $question->id,
                                'option_text' => $optData['text'],
                                'is_correct' => (bool) $optData['is_correct'],
                            ]);
                        }
                    }
                }
            }

            return redirect()->back()->with('success', 'Gemini AI successfully generated and published '.count($aiResult['questions'] ?? []).' assessment questions!');

        } catch (\Throwable $e) {
            Log::error('AI Assessment Generation Error: '.$e->getMessage());

            return redirect()->back()->with('error', 'Failed to generate AI assessment: '.$e->getMessage());
        }
    }

    /**
     * API endpoint for Gemini AI Assistant Chatbot.
     */
    public function chatReply(Request $request, GeminiAiService $aiService)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');
        $userRole = auth()->check() ? auth()->user()->role : 'guest';

        $reply = $aiService->generateChatResponse($userMessage, $userRole);

        return response()->json([
            'success' => true,
            'reply' => $reply,
            'timestamp' => now()->format('h:i A'),
        ]);
    }
}
