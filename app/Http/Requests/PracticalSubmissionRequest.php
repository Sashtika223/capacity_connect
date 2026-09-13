<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PracticalSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'option_id' => 'required|exists:practical_options,id',
        ];
    }
}
