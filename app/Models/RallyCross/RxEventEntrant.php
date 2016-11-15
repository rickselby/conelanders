<?php

namespace App\Models\RallyCross;

use App\Models\Driver;

class RxEventEntrant extends \Eloquent
{
    public function event()
    {
        return $this->belongsTo(RxEvent::class, 'rx_event_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function car()
    {
        return $this->belongsTo(RxCar::class, 'rx_car_id');
    }

    public function entries()
    {
        return $this->hasMany(RxSessionEntrant::class);
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
}
