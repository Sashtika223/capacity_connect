<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiContentDraft extends Model
{
    protected $fillable = [
        'user_id', 'source_file_name', 'source_file_path', 'source_pasted_content',
        'status', 'draft_difficulty',
        'draft_title', 'draft_description', 'draft_outline', 'draft_summary',
        'draft_objectives', 'draft_modules', 'draft_notes', 'draft_mcqs', 'draft_practice_questions',
    ];

    protected $casts = [
        'draft_outline' => 'array',
        'draft_objectives' => 'array',
        'draft_mcqs' => 'array',
        'draft_modules' => 'array',
        'draft_notes' => 'array',
        'draft_practice_questions' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
