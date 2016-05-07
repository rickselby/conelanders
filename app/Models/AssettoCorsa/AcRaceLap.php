<?php

namespace App\Models\AssettoCorsa;

class AcRaceLap extends \Eloquent
{
    protected $fillable = ['time'];

    protected $casts = [
        'time' => 'integer',
    ];

    public function entrant()
    {
        return $this->belongsTo(AcEntrant::class, 'ac_race_entrant_id');
    }

    public function lap()
    {
        return $this->belongsTo(AcLaptime::class, 'ac_laptime_id');
    }
}
