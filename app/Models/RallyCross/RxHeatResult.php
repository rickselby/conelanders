<?php

namespace App\Models\RallyCross;

use App\Models\Driver;

class RxHeatResult extends \Eloquent
{
    protected $fillable = ['position', 'points'];

    protected $casts = [
        'position' => 'integer',
        'points' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(RxEvent::class, 'rx_event_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
