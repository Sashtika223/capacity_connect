<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['course_module_id', 'title', 'content', 'video_url', 'duration', 'order'];

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    public function resources()
    {
        return $this->hasMany(LearningResource::class);
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }
}
