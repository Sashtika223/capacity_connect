<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticalNode extends Model
{
    use HasFactory;

    protected $fillable = [
        'practical_assessment_id',
        'title',
        'situation_text',
        'is_start',
    ];

    public function assessment()
    {
        return $this->belongsTo(PracticalAssessment::class, 'practical_assessment_id');
    }

    public function options()
    {
        return $this->hasMany(PracticalOption::class, 'practical_node_id');
    }
}
