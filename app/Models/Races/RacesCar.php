<?php

namespace App\Models\Races;

class RacesCar extends \Eloquent
{
    protected $fillable = ['races_identifier', 'name', 'short_name'];

    public function entrants()
    {
        return $this->hasMany(RacesSessionEntrant::class);
    }

    /**
     * Get only cars used in the given championship
     *
     * @param $query
     * @param RacesChampionship $championship
     */
    public function scopeForChampionship($query, RacesChampionship $championship)
    {
        return $query->leftJoin('races_session_entrants', 'races_session_entrants.races_car_id', '=', 'races_cars.id')
            ->leftJoin('races_sessions', 'races_sessions.id', '=', 'races_session_entrants.races_session_id')
            ->leftJoin('races_events', 'races_events.id', '=', 'races_sessions.races_event_id')
            ->leftJoin('races_championships', 'races_championships.id', '=', 'races_events.races_championship_id')
            ->where('races_championships.id', '=', $championship->id)
            ->select('races_cars.*')
            ->distinct();
    }

}
