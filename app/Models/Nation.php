<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Nation extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name', 'acronym', 'dirt_reference'];

    protected $sluggable = [
        'build_from' => 'acronym',
    ];

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
