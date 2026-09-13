<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraineeProfile extends Model
{
    protected $fillable = [
        'user_id', 'phone', 'profile_photo', 'qualifications',
        'work_experience', 'designation', 'department', 'interests',
        'skills', 'certificates_list',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCompletionPercentageAttribute()
    {
        $fields = ['phone', 'profile_photo', 'qualifications', 'work_experience', 'designation', 'department', 'interests', 'skills'];
        $filled = 0;
        foreach ($fields as $field) {
            if (! empty($this->{$field})) {
                $filled++;
            }
        }

        return round(($filled / count($fields)) * 100);
    }
}
