<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;

class PointsSequence extends \Eloquent
{
    use Sluggable;

    protected $fillable = ['name'];

    public function points()
    {
        return $this->hasMany(Point::class)->orderBy('position');
    }

    /**
     * Sluggable configuration
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
