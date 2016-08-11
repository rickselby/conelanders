<?php

namespace App\Models\AssettoCorsa;

use App\Models\Driver;

class AcChampionshipEntrant extends \Eloquent
{
    protected $fillable = ['driver_id', 'rookie', 'number', 'css'];

    protected $casts = [
        'rookie' => 'boolean',
    ];

    public function championship()
    {
        return $this->belongsTo(AcChampionship::class, 'ac_championship_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function entries()
    {
        return $this->hasMany(AcSessionEntrant::class);
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
