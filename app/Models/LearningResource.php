<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningResource extends Model
{
    protected $fillable = ['lesson_id', 'title', 'file_path', 'type'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
