<?php

namespace App\Models\DirtRally;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use RickSelby\EloquentSluggableKeyed\SluggableKeyedTrait;

class DirtSeason extends \Eloquent implements SluggableInterface
{
    use SluggableKeyedTrait;

    protected $fillable = ['name'];

    protected $sluggable = [
        'unique_key' => 'dirt_championship_id',
    ];

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
        return $this->championship->name.' - '.$this->name;
    }
}
