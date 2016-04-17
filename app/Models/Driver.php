<?php

namespace App\Models;

class Driver extends \Eloquent
{
    protected $fillable = ['name'];

    public function nation()
    {
        return $this->belongsTo(Nation::class);
    }
}
