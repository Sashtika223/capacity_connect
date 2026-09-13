<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticalAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'course_id',
        'created_by',
        'status',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function nodes()
    {
        return $this->hasMany(PracticalNode::class);
    }

    public function startNode()
    {
        return $this->hasOne(PracticalNode::class)->where('is_start', true);
    }

    public function attempts()
    {
        return $this->hasMany(PracticalAttempt::class);
    }
}
