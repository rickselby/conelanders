<?php

namespace App\Models;

class PointsSequence extends \Eloquent
{
    public function points()
    {
        return $this->hasMany(Point::class);
    }
}
