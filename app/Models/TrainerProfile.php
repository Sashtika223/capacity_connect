<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerProfile extends Model
{
    protected $fillable = [
        'user_id', 'photo', 'qualification', 'experience',
        'expertise', 'department', 'bio', 'subjects',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Compute dynamic profile completion percentage (backend-enforced).
     */
    public function getCompletionPercentageAttribute(): int
    {
        $fields = ['photo', 'qualification', 'experience', 'expertise', 'department', 'bio', 'subjects'];
        $filled = 0;

        foreach ($fields as $field) {
            if (! empty($this->{$field})) {
                $filled++;
            }
        }

        return (int) round(($filled / count($fields)) * 100);
    }

    /**
     * Get list of missing profile fields with user-friendly names.
     */
    public function getMissingFieldsAttribute(): array
    {
        $fieldLabels = [
            'photo' => 'Profile Photo',
            'qualification' => 'Qualifications & Degrees',
            'experience' => 'Years of Experience',
            'expertise' => 'Areas of Expertise',
            'department' => 'Department',
            'bio' => 'Professional Bio',
            'subjects' => 'Subjects / Courses Offered',
        ];

        $missing = [];
        foreach ($fieldLabels as $field => $label) {
            if (empty($this->{$field})) {
                $missing[] = $label;
            }
        }

        return $missing;
    }

    /**
     * Check if profile is 100% complete.
     */
    public function isComplete(): bool
    {
        return $this->completion_percentage === 100;
    }
}
