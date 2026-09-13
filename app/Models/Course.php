<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title', 'course_code', 'description', 'category_id', 'trainer_id',
        'thumbnail', 'difficulty', 'duration', 'learning_objectives',
        'prerequisites', 'status', 'publish_status', 'is_featured',
        'start_date', 'end_date',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function competencies()
    {
        return $this->belongsToMany(Competency::class)
            ->withPivot('level')
            ->withTimestamps();
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function category()
    {
        return $this->belongsTo(CourseCategory::class);
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function modules()
    {
        return $this->hasMany(CourseModule::class);
    }
}
