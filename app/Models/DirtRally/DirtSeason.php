<?php

namespace App\Models\DirtRally;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DirtSeason extends \Eloquent
{
    use Sluggable;

    protected $fillable = ['name'];

    public function championship()
    {
        return $this->belongsTo(DirtChampionship::class, 'dirt_championship_id');
    }

    public function events()
    {
        return $this->hasMany(DirtEvent::class)->orderBy('closes');
    }

    public function getOpensAttribute()
    {
        $dates = [];
        foreach($this->events AS $event) {
            $dates[] = $event->opens;
        }
        if (count($dates)) {
            return min($dates);
        } else {
            // No events; push to bottom of list
            return Carbon::now();
        }
    }

    public function getClosesAttribute()
    {
        $dates = [];
        foreach($this->events AS $event) {
            $dates[] = $event->closes;
        }
        if (count($dates)) {
            return max($dates);
        } else {
            // No events; push to bottom of list
            return Carbon::now();
        }
    }

    public function getStageCountAttribute()
    {
        $stages = 0;
        foreach($this->events AS $event) {
            $stages += count($event->stages);
        }
        return $stages;
    }

    public function getFullNameAttribute()
    {
        return $this->championship->name.' - '.$this->name;
    }

    public function isComplete() {
        foreach($this->events AS $event) {
            if (!$event->isComplete()) {
                return false;
            }
        }
        return true;
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
        return $query->where('dirt_championship_id', $model->dirt_championship_id);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

}
