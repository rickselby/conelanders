<?php

namespace App\Models\RallyCross;

class RxCar extends \Eloquent
{
    protected $fillable = ['name', 'short_name'];

    public function entrants()
    {
        return $this->hasMany(RxEventEntrant::class);
    }

    /**
     * Get only cars used in the given championship
     *
     * @param $query
     * @param RxChampionship $championship
     */
    public function scopeForChampionship($query, RxChampionship $championship)
    {
        return $query->leftJoin('rx_event_entrants', 'rx_event_entrants.rx_car_id', '=', 'rx_cars.id')
            ->leftJoin('rx_events', 'rx_events.id', '=', 'rx_event_entrants.rx_event_id')
            ->where('rx_events.rx_championship_id', '=', $championship->id)
            ->select('rx_cars.*')
            ->distinct();
    }    
}
