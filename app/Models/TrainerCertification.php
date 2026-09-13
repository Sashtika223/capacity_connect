<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerCertification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'assessment_id',
        'status',
        'score',
        'passing_score',
        'validity_years',
        'issued_at',
        'expires_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'score' => 'decimal:2',
    ];

    public function trainer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function attempts()
    {
        return $this->hasMany(TrainerCertificationAttempt::class, 'trainer_certification_id');
    }

    /**
     * Compute dynamic status based on dates & database records.
     */
    public function getCalculatedStatusAttribute(): string
    {
        if (in_array($this->status, ['assigned', 'pending', 'renewal_failed', 'profile_incomplete'])) {
            return $this->status;
        }

        if ($this->status === 'certified' || $this->status === 'renewal_passed') {
            if ($this->expires_at && Carbon::now()->greaterThan($this->expires_at)) {
                return 'expired';
            }
            if ($this->expires_at && Carbon::now()->diffInDays($this->expires_at, false) <= 30) {
                return 'renewal_required';
            }

            return 'certified';
        }

        return $this->status;
    }

    public function isExpired(): bool
    {
        return $this->expires_at ? Carbon::now()->greaterThan($this->expires_at) : false;
    }

    public function getBadgeClassAttribute(): string
    {
        return $this->statusBadgeClass();
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->statusLabel();
    }

    public function statusBadgeClass(): string
    {
        return match ($this->calculated_status) {
            'certified' => 'bg-success text-white',
            'certification_assigned', 'assigned' => 'bg-primary text-white',
            'certification_pending', 'pending' => 'bg-warning text-dark',
            'certification_expired', 'expired' => 'bg-danger text-white',
            'renewal_required' => 'bg-warning text-dark',
            'renewal_passed' => 'bg-success text-white',
            'renewal_failed' => 'bg-danger text-white',
            'eligible' => 'bg-info text-dark',
            default => 'bg-secondary text-white',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->calculated_status) {
            'certified' => 'Certified',
            'certification_assigned', 'assigned' => 'Certification Assigned',
            'certification_pending', 'pending' => 'Certification Pending',
            'certification_expired', 'expired' => 'Certification Expired',
            'renewal_required' => 'Renewal Required',
            'renewal_passed' => 'Renewal Passed',
            'renewal_failed' => 'Renewal Failed',
            'eligible' => 'Eligible for Certification',
            default => 'Profile Incomplete',
        };
    }

    public function getDigitalProfileLevelAttribute(): string
    {
        return static::calculateDigitalProfileLevel($this->score);
    }

    public static function calculateDigitalProfileLevel($score): string
    {
        if ($score === null) {
            return 'Unrated';
        }
        $val = (float) $score;
        if ($val >= 80) {
            return 'Master Trainer';
        }
        if ($val >= 60) {
            return 'Intermediate Trainer';
        }

        return 'Beginner Trainer';
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
