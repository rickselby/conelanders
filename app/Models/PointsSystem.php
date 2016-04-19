<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class PointsSystem extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name', 'default'];

    public function eventSequence()
    {
        return $this->belongsTo(PointsSequence::class, 'event_points_sequence');
    }

    public function stageSequence()
    {
        return $this->belongsTo(PointsSequence::class, 'stage_points_sequence');
    }
}
