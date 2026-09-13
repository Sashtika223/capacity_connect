<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticalOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'practical_node_id',
        'option_text',
        'next_node_id',
        'consequence_text',
        'score_delta',
        'feedback',
    ];

    public function node()
    {
        return $this->belongsTo(PracticalNode::class, 'practical_node_id');
    }

    public function nextNode()
    {
        return $this->belongsTo(PracticalNode::class, 'next_node_id');
    }
}
