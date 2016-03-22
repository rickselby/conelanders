<?php

namespace App\Models;

class Season extends \Eloquent
{
    protected $fillable = ['name'];

    public function events()
    {
        return $this->hasMany(Event::class)->orderBy('closes');
    }

    public function getEndDateAttribute()
    {
        $dates = [];
        foreach($this->events AS $event) {
            $dates[] = $event->closes;
        }
        return max($dates);
    }
}
