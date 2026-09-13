<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'trainer_status',
        'approved_at',
        'rejected_at',
        'rejection_reason',
        'approved_by',
        'expert_verified',
        'expert_role',
        'in_knowledge_repo',
        'points',
        'level',
        'learning_streak',
        'last_active_at',
    ];

    public function isTrainee()
    {
        return $this->role === 'trainee';
    }

    public function isTrainer()
    {
        return $this->role === 'trainer';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPendingTrainer(): bool
    {
        return $this->isTrainer() && $this->trainer_status === 'pending';
    }

    public function isApprovedTrainer(): bool
    {
        return $this->isTrainer() && $this->trainer_status === 'approved';
    }

    public function isRejectedTrainer(): bool
    {
        return $this->isTrainer() && $this->trainer_status === 'rejected';
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function traineeProfile()
    {
        return $this->hasOne(TraineeProfile::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function createdAnnouncements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    public function competencies()
    {
        return $this->belongsToMany(Competency::class)
            ->withPivot('current_level', 'required_level')
            ->withTimestamps();
    }

    public function trainerCourses()
    {
        return $this->hasMany(Course::class, 'trainer_id');
    }

    public function trainerProfile()
    {
        return $this->hasOne(TrainerProfile::class);
    }

    public function trainerCertifications()
    {
        return $this->hasMany(TrainerCertification::class, 'user_id');
    }

    public function getTrainerEligibilityStatusAttribute(): string
    {
        if (! $this->isTrainer()) {
            return 'N/A';
        }

        $profile = $this->trainerProfile;
        if (! $profile || ! $profile->isComplete()) {
            return 'Profile Incomplete';
        }

        $certs = $this->trainerCertifications;
        if ($certs->isEmpty()) {
            return 'Eligible for Certification';
        }

        // Return highest priority certification status
        if ($certs->contains(fn ($c) => $c->calculated_status === 'renewal_required' || $c->calculated_status === 'expired')) {
            return 'Renewal Required';
        }
        if ($certs->contains(fn ($c) => $c->calculated_status === 'assigned')) {
            return 'Certification Assigned';
        }
        if ($certs->contains(fn ($c) => $c->calculated_status === 'certified' || $c->calculated_status === 'renewal_passed')) {
            return 'Certified';
        }

        return 'Eligible for Certification';
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function assessmentAttempts()
    {
        return $this->hasMany(AssessmentAttempt::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('awarded_at');
    }

    public function notificationPreference()
    {
        return $this->hasOne(UserNotificationPreference::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_active_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
