<?php

namespace App\Models\RallyCross;

use App\Models\Driver;

class RxHeatResult extends \Eloquent
{
    protected $fillable = ['rx_event_entrant_id', 'position', 'points'];

    protected $casts = [
        'position' => 'integer',
        'points' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(RxEvent::class, 'rx_event_id');
    }

    public function entrant()
    {
        return $this->belongsTo(RxEventEntrant::class, 'rx_event_entrant_id');
    }
}
