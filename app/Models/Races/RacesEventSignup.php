<?php

namespace App\Models\Races;

class RacesEventSignup extends \Eloquent
{
    protected $fillable = ['status', 'races_championship_entrant_id'];

    public function event()
    {
        return $this->belongsTo(RacesEvent::class, 'races_event_id');
    }

    public function entrant()
    {
        return $this->belongsTo(RacesChampionshipEntrant::class, 'races_championship_entrant_id');
    }

}
