<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Driver extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name', 'nation_id', 'dirt_racenet_driver_id', 'ac_guid', 'locked'];

    public function nation()
    {
        return $this->belongsTo(Nation::class);
    }

    public function dirtResults()
    {
        return $this->hasMany(DirtRally\DirtResult::class);
    }

    public function acEntries()
    {
        return $this->hasMany(AssettoCorsa\AcChampionshipEntrant::class);
    }
    
    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
