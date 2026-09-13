<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResourceUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isTrainer() || auth()->user()->isAdmin());
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|in:pdf,video,document,audio,archive',
            'resource_file' => 'required_without:external_url|nullable|file|mimes:pdf,doc,docx,mp4,mp3,zip,txt|max:51200', // 50MB max
            'external_url' => 'nullable|url|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Resource title is mandatory.',
            'resource_file.mimes' => 'Allowed file formats: PDF, DOC, DOCX, MP4, MP3, ZIP, TXT.',
            'resource_file.max' => 'File size cannot exceed 50 MB.',
        ];
    }
}
