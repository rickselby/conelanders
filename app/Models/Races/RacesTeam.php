<?php

namespace App\Models\Races;

class RacesTeam extends \Eloquent
{
    protected $fillable = ['name', 'short_name', 'races_car_id', 'css'];

    public function championship()
    {
        return $this->belongsTo(RacesChampionship::class, 'races_championship_id');
    }

    public function car()
    {
        return $this->belongsTo(RacesCar::class, 'races_car_id');
    }

    public function entrants()
    {
        return $this->hasMany(RacesChampionshipEntrant::class);
    }

    public function scopeOrderByName($query)
    {
        $table = $this->driver()->getRelated()->getTable();

        $query->orderBy($table.'.name')
            ->select($this->getTable().'.*');
    }

    public function getSortedEntrantsAttribute()
    {
        return $this->entrants()
            ->with('driver.nation')
            ->orderByNumber()
            ->get();
    }
}