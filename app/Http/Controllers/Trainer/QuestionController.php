<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request, Assessment $assessment)
    {
        $request->validate([
            'question_text' => 'required|string',
            'marks' => 'required|integer|min:1',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0',
        ]);

        $question = $assessment->questions()->create([
            'question_text' => $request->question_text,
            'marks' => $request->marks,
        ]);

        foreach ($request->options as $index => $optionText) {
            $question->options()->create([
                'option_text' => $optionText,
                'is_correct' => $index == $request->correct_option,
            ]);
        }

        return back()->with('success', 'Question added successfully.');
    }

    public function destroy(AssessmentQuestion $question)
    {
        $question->delete();

        return back()->with('success', 'Question removed successfully.');
    }
}
