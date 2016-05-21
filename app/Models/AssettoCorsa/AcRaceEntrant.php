<?php

namespace App\Models\AssettoCorsa;

class AcRaceEntrant extends \Eloquent
{
    protected $fillable = ['ballast', 'car'];

    protected $casts = [
        'ballast' => 'integer',
        'qualifying_position' => 'integer',
        'race_position' => 'integer',
        'race_time' => 'integer',
        'race_behind' => 'integer',
        'race_fastest_lap_position' => 'integer',
        'race_disqualified' => 'boolean',
        'race_retired' => 'boolean',
    ];

    public function race()
    {
        return $this->belongsTo(AcRace::class, 'ac_race_id');
    }

    public function championshipEntrant()
    {
        return $this->belongsTo(AcChampionshipEntrant::class, 'ac_championship_entrant_id');
    }

    public function qualifyingLap()
    {
        return $this->belongsTo(AcLaptime::class, 'qualifying_lap_id');
    }

    public function raceFastestLap()
    {
        return $this->belongsTo(AcLaptime::class, 'race_fastest_lap_id');
    }

    public function raceLaps()
    {
        return $this->hasMany(AcRaceLap::class);
    }

    public function canBeDeleted()
    {
        return (count($this->raceLaps) == 0) && !$this->qualifyingLap;
    }
}
