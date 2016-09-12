<?php

namespace App\Models\AssettoCorsa;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class AcCar extends \Eloquent
{
    protected $fillable = ['ac_identifier', 'name', 'full_name'];

    public function entrants()
    {
        return $this->hasMany(AcSessionEntrant::class);
    }

    /**
     * Get only cars used in the given championship
     *
     * @param $query
     * @param AcChampionship $championship
     */
    public function scopeForChampionship($query, AcChampionship $championship)
    {
        return $query->leftJoin('ac_session_entrants', 'ac_session_entrants.ac_car_id', '=', 'ac_cars.id')
            ->leftJoin('ac_sessions', 'ac_sessions.id', '=', 'ac_session_entrants.ac_session_id')
            ->leftJoin('ac_events', 'ac_events.id', '=', 'ac_sessions.ac_event_id')
            ->leftJoin('ac_championships', 'ac_championships.id', '=', 'ac_events.ac_championship_id')
            ->where('ac_championships.id', '=', $championship->id)
            ->select('ac_cars.*')
            ->distinct();
    }

}
