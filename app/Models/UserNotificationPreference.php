<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'in_app_notifications',
        'email_notifications',
        'new_course_alerts',
        'assessment_alerts',
        'certificate_alerts',
        'announcement_alerts',
        'skill_gap_alerts',
    ];

    protected $casts = [
        'in_app_notifications' => 'boolean',
        'email_notifications' => 'boolean',
        'new_course_alerts' => 'boolean',
        'assessment_alerts' => 'boolean',
        'certificate_alerts' => 'boolean',
        'announcement_alerts' => 'boolean',
        'skill_gap_alerts' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
