<?php

namespace App\Models\AssettoCorsa;

class AcSessionLap extends \Eloquent
{
    protected $fillable = ['time'];

    protected $casts = [
        'time' => 'integer',
    ];

    public function entrant()
    {
        return $this->belongsTo(AcSessionEntrant::class, 'ac_session_entrant_id');
    }

    public function lap()
    {
        return $this->belongsTo(AcLaptime::class, 'ac_laptime_id');
    }
}
