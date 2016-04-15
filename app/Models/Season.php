<?php

namespace App\Models;

use Carbon\Carbon;

class Season extends \Eloquent
{
    protected $fillable = ['name'];

    public function championship()
    {
        return $this->belongsTo(Championship::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class)->orderBy('closes');
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

    public function isComplete() {
        foreach($this->events AS $event) {
            if (!$event->isComplete()) {
                return false;
            }
        }
        return true;
    }
}
