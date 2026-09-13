<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticalAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'practical_assessment_id',
        'user_id',
        'final_score',
        'status',
        'start_time',
        'end_time',
    ];

    public function assessment()
    {
        return $this->belongsTo(PracticalAssessment::class, 'practical_assessment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(PracticalAttemptLog::class);
    }
}
