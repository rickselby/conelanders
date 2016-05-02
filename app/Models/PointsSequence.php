<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class PointsSequence extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name'];

    public function points()
    {
        return $this->hasMany(Point::class)->orderBy('position');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
