<?php

namespace App\Models\DirtRally;

class PointsSequence extends \Eloquent
{
    public function points()
    {
        return $this->hasMany(Point::class)->orderBy('position');
    }
}
