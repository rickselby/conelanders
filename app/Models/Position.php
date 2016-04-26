<?php

namespace App\Models;

class Position extends \Eloquent
{
    protected $fillable = ['driver_id', 'position'];

    protected $casts = [
        'position' => 'integer',
    ];

    public function positionable()
    {
        return $this->morphTo();
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
