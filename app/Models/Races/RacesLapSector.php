<?php

namespace App\Models\Races;

class RacesLapSector extends \Eloquent
{
    protected $fillable = ['sector', 'time'];

    protected $casts = [
        'sector' => 'integer',
        'time' => 'integer',
    ];

    public function lap()
    {
        return $this->belongsTo(RacesLap::class, 'races_laptime_id');
    }
}
