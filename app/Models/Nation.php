<?php

namespace App\Models;

class Nation extends \Eloquent
{
    protected $fillable = ['name', 'acronym', 'dirt_reference'];

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }
}
