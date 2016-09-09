<?php

namespace App\Models\AssettoCorsa;

class AcSessionEntrant extends \Eloquent
{
    protected $fillable = ['ballast', 'car', 'ac_championship_entrant_id'];

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
    ];

    public function session()
    {
        return $this->belongsTo(AcSession::class, 'ac_session_id');
    }

    public function championshipEntrant()
    {
        return $this->belongsTo(AcChampionshipEntrant::class, 'ac_championship_entrant_id');
    }

    public function fastestLap()
    {
        return $this->belongsTo(AcLaptime::class, 'fastest_lap_id');
    }

    public function laps()
    {
        return $this->hasMany(AcSessionLap::class);
    }

    public function car()
    {
        return $this->belongsTo(AcCar::class, 'ac_car_id');
    }

    public function canBeDeleted()
    {
        return (count($this->laps) == 0);
    }
}
