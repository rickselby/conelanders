<?php

namespace App\Models\Races;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class RacesCategory extends \Eloquent
{
    use Sluggable, SluggableScopeHelpers;

    protected $fillable = [
        'name',
    ];

    public function championships()
    {
        return $this->hasMany(RacesChampionship::class);
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
