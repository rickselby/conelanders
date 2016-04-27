<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Driver extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name', 'dirt_racenet_driver_id'];

    public function nation()
    {
        return $this->belongsTo(Nation::class);
    }

    public function dirtResults()
    {
        return $this->hasMany(DirtRally\DirtResult::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
