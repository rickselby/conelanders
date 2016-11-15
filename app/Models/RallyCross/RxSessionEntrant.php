<?php

namespace App\Models\RallyCross;

use App\Models\Driver;

class RxSessionEntrant extends \Eloquent
{
    protected $fillable = ['race', 'time', 'penalty', 'dnf', 'dsq'];

    protected $casts = [
        'time' => 'integer',
        'position' => 'integer',
        'penalty' => 'integer',
        'points' => 'integer',
    ];

    public function session()
    {
        return $this->belongsTo(RxSession::class, 'rx_session_id');
    }

    public function eventEntrant()
    {
        return $this->belongsTo(RxEventEntrant::class, 'rx_event_entrant_id');
    }

    public function getTotalTimeAttribute()
    {
        return $this->time + $this->penalty;
    }
}
