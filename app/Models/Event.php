<?php

namespace App\Models;

class Event extends \Eloquent
{
    protected $fillable = ['name', 'closes'];

    protected $dates = ['closes'];

    public function season()
    {
        return $this->hasOne(Season::class);
    }

    public function stages()
    {
        return $this->hasMany(Stage::class);
    }
}
