<?php

namespace App\Models\DirtRally;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DirtStage extends \Eloquent
{
    use Sluggable;

    protected $fillable = ['name', 'order', 'long'];

    protected $casts = [
        'order' => 'integer',
        'long' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(DirtEvent::class, 'dirt_event_id');
    }

    public function results()
    {
        return $this->hasMany(DirtResult::class)->orderBy('position');
    }

    public function getFullNameAttribute()
    {
        return $this->event->fullName.' - '.$this->name;
    }
    
    public function getSSAttribute()
    {
        return 'SS'.$this->order;
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
        return $query->where('dirt_event_id', $model->dirt_event_id);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

}
