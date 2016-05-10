<?php

namespace App\Models\AssettoCorsa;

use App\Models\PointsSequence;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class AcPointsSystem extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name', 'default', 'race_points_sequence', 'laps_points_sequence'];

    public function raceSequence()
    {
        return $this->belongsTo(PointsSequence::class, 'race_points_sequence');
    }

    public function lapsSequence()
    {
        return $this->belongsTo(PointsSequence::class, 'laps_points_sequence');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
