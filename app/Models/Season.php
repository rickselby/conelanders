<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Season extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name'];

    protected $sluggable = [
        'unique' => false,
    ];

    public function championship()
    {
        return $this->belongsTo(Championship::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class)->orderBy('closes');
    }

    public function positions()
    {
        return $this->morphMany('App\Models\Position', 'positionable');
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

    public function isComplete() {
        foreach($this->events AS $event) {
            if (!$event->isComplete()) {
                return false;
            }
        }
        return true;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getFullNameAttribute()
    {
        return $this->championship->name
            .' - '.$this->name;
    }
}
