<?php

namespace App\Models\DirtRally;

class DirtCar extends \Eloquent
{
    protected $fillable = ['name', 'short_name'];

    public function entrants()
    {
        return $this->hasMany(RacesSessionEntrant::class);
    }

    public function getShortNameAttribute()
    {
        return $this->short_name ?: $this->name;
    }

    /**
     * Get only cars used in the given championship
     *
     * @param $query
     * @param RacesChampionship $championship
     *
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
     */

}
