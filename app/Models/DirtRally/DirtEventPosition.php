<?php

namespace App\Models\DirtRally;

use App\Models\Driver;

class DirtEventPosition extends \Eloquent
{
    protected $fillable = ['event_id', 'driver_id', 'position'];

    protected $casts = [
        'position' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(DirtEvent::class, 'dirt_event_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
