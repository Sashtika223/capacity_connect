<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticalAttemptLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'practical_attempt_id',
        'node_id',
        'option_id',
        'score_delta',
        'consequence_text',
    ];

    public function attempt()
    {
        return $this->belongsTo(PracticalAttempt::class, 'practical_attempt_id');
    }

    public function node()
    {
        return $this->belongsTo(PracticalNode::class, 'node_id');
    }

    public function option()
    {
        return $this->belongsTo(PracticalOption::class, 'option_id');
    }
}
