<?php

namespace App\Models\AssettoCorsa;

class AcTeam extends \Eloquent
{
    protected $fillable = ['name', 'short_name', 'ac_car_id'];

    public function championship()
    {
        return $this->hasOne(AcChampionship::class);
    }

    public function car()
    {
        return $this->belongsTo(AcCar::class, 'ac_car_id');
    }

    public function entrants()
    {
        return $this->hasMany(AcChampionshipEntrant::class);
    }

    public function scopeOrderByName($query)
    {
        $relation = $this->driver();
        $related = $relation->getRelated();
        $table = $related->getTable();
        $foreignKey = $relation->getForeignKey();

        $query->orderBy($table.'.name')
            ->select($this->getTable().'.*');
    }

    public function getSortedEntrantsAttribute()
    {
        return $this->entrants()->with('driver.nation')->orderByName()->get();
    }
}