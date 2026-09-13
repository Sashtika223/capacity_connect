<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssessmentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isTrainer() || auth()->user()->isAdmin());
    }

    public function rules(): array
    {
        return [
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
        ];
    }
}
