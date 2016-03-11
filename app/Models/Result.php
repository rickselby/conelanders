<?php

namespace App\Models;

class Result extends \Eloquent
{
    protected $fillable = ['time'];

    public function stage()
    {
        return $this->hasOne(Stage::class);
    }

    public function driver()
    {
        return $this->hasOne(Driver::class);
    }
}
