<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;

class Driver extends \Eloquent
{
    use Sluggable;

    protected $fillable = ['name', 'nation_id', 'dirt_racenet_driver_id', 'steam_id', 'locked'];

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
        return $this->hasMany(Races\RacesChampionshipEntrant::class);
    }
    
    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function scopeNotLinkedToUser($query)
    {
        $relation = $this->user();
        $related = $relation->getRelated();
        $table = $related->getTable();
        $foreignKey = $relation->getForeignKey();

        $query->leftJoin($table, $this->getQualifiedKeyName(), '=', $foreignKey)
            ->whereNull($related->getQualifiedKeyName())
            ->select($this->getTable().'.*');
    }

    /**
     * Sluggable configuration
     *
     * @return array
     */
    public function sluggable()
    {
        // Test what the slug would be if we used 'name' as the source
        $possibleSlug = SlugService::createSlug(self::class, 'slug', $this->name, ['source' => 'name']);
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
