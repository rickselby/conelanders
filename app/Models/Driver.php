<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Driver extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name'];

    public function nation()
    {
        return $this->belongsTo(Nation::class);
    }
}
