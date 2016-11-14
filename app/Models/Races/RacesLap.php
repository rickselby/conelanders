<?php

namespace App\Models\Races;

class RacesLap extends \Eloquent
{
    protected $fillable = ['laptime', 'time_set'];

    protected $casts = [
        'laptime' => 'integer',
        'time_set' => 'integer',
    ];

    public function sessionEntrant()
    {
        return $this->belongsTo(RacesSessionEntrant::class, 'races_session_entrant_id');
    }

    public function sectors()
    {
        return $this->hasMany(RacesLapSector::class);
    }
}
