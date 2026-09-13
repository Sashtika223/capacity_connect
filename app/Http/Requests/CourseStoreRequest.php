<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isTrainer() || auth()->user()->isAdmin());
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:course_categories,id',
            'department' => 'nullable|string|max:255',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'required|integer|min:1',
            'publish_status' => 'required|in:draft,published,archived',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Course title is mandatory.',
            'category_id.required' => 'Please select a valid course category.',
            'difficulty.in' => 'Course difficulty must be beginner, intermediate, or advanced.',
        ];
    }
}
