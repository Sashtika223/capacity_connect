<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
    protected $fillable = [
        'name', 'description', 'type',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('current_level', 'required_level')
            ->withTimestamps();
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class)
            ->withPivot('level')
            ->withTimestamps();
    }
}
