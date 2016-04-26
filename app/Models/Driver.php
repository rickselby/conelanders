<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Driver extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name', 'racenet_id'];

    public function nation()
    {
        return $this->belongsTo(Nation::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
