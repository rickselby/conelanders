<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;

class Driver extends \Eloquent
{
    use Sluggable;

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

    /**
     * Sluggable configuration
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
