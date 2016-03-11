<?php

namespace App\Models;

class Season extends \Eloquent
{
    protected $fillable = ['name'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
