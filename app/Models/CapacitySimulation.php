<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapacitySimulation extends Model
{
    protected $fillable = [
        'name', 'competency_id', 'current_employees', 'current_capacity',
        'required_capacity', 'employees_to_train', 'expected_improvement',
        'projected_capacity', 'remaining_gap', 'improvement_percentage',
        'training_duration_days', 'training_sessions', 'trainers_required',
        'training_hours_total', 'risk_reduction_percentage',
    ];

    public function competency()
    {
        return $this->belongsTo(Competency::class);
    }
}
