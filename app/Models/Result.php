<?php

namespace App\Models;

class Result extends \Eloquent
{
    protected $fillable = ['driver_id', 'time', 'dnf'];

    protected $casts = [
        'time' => 'integer',
        'dnf' => 'boolean',
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
