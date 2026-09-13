<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'hero_title' => 'required|string|max:255',
            'hero_description' => 'required|string',
            'important_notice' => 'nullable|string',
            'show_ai_section' => 'nullable|boolean',
            'show_disaster_section' => 'nullable|boolean',
            'show_offline_section' => 'nullable|boolean',
        ];
    }
}
