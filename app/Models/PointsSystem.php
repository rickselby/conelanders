<?php

namespace App\Models;

class PointsSystem extends \Eloquent
{
    protected $fillable = ['name'];

    public function eventSequence()
    {
        return $this->belongsTo(PointsSequence::class, 'event_points_sequence');
    }

    public function stageSequence()
    {
        return $this->belongsTo(PointsSequence::class, 'stage_points_sequence');
    }
}
