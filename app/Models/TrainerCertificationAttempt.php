<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerCertificationAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainer_certification_id',
        'user_id',
        'assessment_id',
        'assessment_attempt_id',
        'type',
        'score',
        'percentage',
        'passed',
        'attempted_at',
    ];

    protected $casts = [
        'passed' => 'boolean',
        'attempted_at' => 'datetime',
        'score' => 'decimal:2',
    ];

    public function certification()
    {
        return $this->belongsTo(TrainerCertification::class, 'trainer_certification_id');
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function assessmentAttempt()
    {
        return $this->belongsTo(AssessmentAttempt::class, 'assessment_attempt_id');
    }

    public function getDigitalProfileLevelAttribute(): string
    {
        return TrainerCertification::calculateDigitalProfileLevel($this->score);
    }

    public function getDigitalProfileBadgeClassAttribute(): string
    {
        return match ($this->digital_profile_level) {
            'Master Trainer' => 'bg-success-subtle text-success border border-success',
            'Intermediate Trainer' => 'bg-warning-subtle text-dark border border-warning',
            'Beginner Trainer' => 'bg-info-subtle text-dark border border-info',
            default => 'bg-light text-muted border',
        };
    }
}
