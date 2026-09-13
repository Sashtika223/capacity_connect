<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_title',
        'hero_description',
        'important_notice',
        'stat_highlights',
        'featured_course_ids',
        'show_ai_section',
        'show_disaster_section',
        'show_offline_section',
    ];

    protected $casts = [
        'stat_highlights' => 'array',
        'featured_course_ids' => 'array',
        'show_ai_section' => 'boolean',
        'show_disaster_section' => 'boolean',
        'show_offline_section' => 'boolean',
    ];
}
