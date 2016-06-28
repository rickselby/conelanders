<?php

namespace App\Models\DirtRally;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DirtEvent extends \Eloquent
{
    use Sluggable;

    protected $fillable = ['name', 'opens', 'closes', 'racenet_event_id'];

    protected $dates = ['opens', 'closes', 'last_import'];

    protected $casts = [
        'racenet_event_id' => 'integer',
        'importing' => 'boolean',
    ];

    public function season()
    {
        return $this->belongsTo(DirtSeason::class, 'dirt_season_id');
    }

    public function stages()
    {
        return $this->hasMany(DirtStage::class)->orderBy('order');
    }

    public function positions()
    {
        return $this->hasMany(DirtEventPosition::class)->orderBy('position');
    }

    public function getFullNameAttribute()
    {
        return $this->season->fullName.' - '.$this->name;
    }

    public function isComplete()
    {
        return $this->closes < $this->last_import;
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

    /**
     * Sluggable configuration
     * Make the slugs unique based on the championship id
     */
    public function scopeWithUniqueSlugConstraints(Builder $query, Model $model, $attribute, $config, $slug)
    {
        return $query->where('dirt_season_id', $model->dirt_season_id);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
