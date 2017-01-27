<?php

namespace App\Models\Races;

class RacesSessionEntrant extends \Eloquent
{
    protected $fillable = ['ballast'];

    protected $casts = [
        'ballast' => 'integer',
        'started' => 'integer',
        'position' => 'integer',
        'time' => 'integer',
        'dsq' => 'boolean',
        'dnf' => 'boolean',
        'fastest_lap_position' => 'integer',
        'points' => 'integer',
        'fastest_lap_points' => 'integer',
        'time_penalty' => 'integer',
    ];

    public function session()
    {
        return $this->belongsTo(RacesSession::class, 'races_session_id');
    }

    public function championshipEntrant()
    {
        return $this->belongsTo(RacesChampionshipEntrant::class, 'races_championship_entrant_id');
    }

    public function fastestLap()
    {
        return $this->belongsTo(RacesLap::class, 'fastest_lap_id');
    }

    public function laps()
    {
        return $this->hasMany(RacesLap::class);
    }

    public function car()
    {
        return $this->belongsTo(RacesCar::class, 'races_car_id');
    }

    public function penalties()
    {
        return $this->hasMany(RacesPenalty::class);
    }

    public function canBeDeleted()
    {
        return (count($this->laps) == 0);
    }

    public function getTotalTimeAttribute()
    {
        return $this->time + $this->time_penalty;
    }
}
