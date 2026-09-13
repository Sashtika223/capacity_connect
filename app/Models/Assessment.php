<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'course_id', 'title', 'subject', 'instructions',
        'duration', 'passing_score', 'start_date', 'end_date', 'status',
        'negative_marking', 'negative_mark_value', 'max_attempts', 'randomize_questions',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'negative_marking' => 'boolean',
        'randomize_questions' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(AssessmentQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(AssessmentAttempt::class);
    }
}
