<?php

namespace App\Models\AssettoCorsa;

class AcEventSignup extends \Eloquent
{
    protected $fillable = ['status', 'ac_championship_entrant_id'];

    public function event()
    {
        return $this->belongsTo(AcEvent::class, 'ac_event_id');
    }

    public function entrant()
    {
        return $this->belongsTo(AcChampionshipEntrant::class, 'ac_championship_entrant_id');
    }

}
