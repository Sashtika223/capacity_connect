<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'qualifications' => 'nullable|string',
            'work_experience' => 'nullable|string',
            'interests' => 'nullable|string',
            'skills' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
