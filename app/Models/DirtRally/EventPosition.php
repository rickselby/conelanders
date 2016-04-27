<?php

namespace App\Models\DirtRally;

use App\Models\Driver;

class EventPosition extends \Eloquent
{
    protected $fillable = ['event_id', 'driver_id', 'position'];

    protected $casts = [
        'position' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
