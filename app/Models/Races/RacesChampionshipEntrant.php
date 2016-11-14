<?php

namespace App\Models\Races;

use App\Models\Driver;

class RacesChampionshipEntrant extends \Eloquent
{
    protected $fillable = [
        'driver_id',
        'rookie',
        'number',
        'css',
        'colour',
        'colour2',
        'races_car_id',
        'races_team_id',
    ];

    protected $casts = [
        'rookie' => 'boolean',
    ];

    public function championship()
    {
        return $this->belongsTo(RacesChampionship::class, 'races_championship_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function entries()
    {
        return $this->hasMany(RacesSessionEntrant::class);
    }

    public function team()
    {
        return $this->belongsTo(RacesTeam::class, 'races_team_id');
    }

    public function car()
    {
        return $this->belongsTo(RacesCar::class, 'races_car_id');
    }

    public function signups()
    {
        return $this->hasMany(RacesEventSignup::class);
    }

    public function scopeOrderByName($query)
    {
        $relation = $this->driver();
        $related = $relation->getRelated();
        $table = $related->getTable();
        $foreignKey = $relation->getForeignKey();

        $query->join($table, $related->getQualifiedKeyName(), '=', $foreignKey)
            ->orderBy($table.'.name')
            ->select($this->getTable().'.*');
    }

    public function scopeNoTeam($query)
    {
        $query->whereNull('races_team_id');
    }

    public function scopeOrderByNumber($query)
    {
        $query->orderByRaw('cast(number as unsigned)');
    }
}
