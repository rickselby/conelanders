<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Services\SlugService;
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
        // The name might generate an empty slug; so use the ID if this is the case
        $possibleSlug = SlugService::createSlug(self::class, 'slug', $this->name);
        return [
            'slug' => [
                'source' => $possibleSlug ? 'name' : 'id',
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
