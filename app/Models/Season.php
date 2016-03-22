<?php

namespace App\Models;

use Carbon\Carbon;

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
        if (count($dates)) {
            return max($dates);
        } else {
            return Carbon::now();
        }
    }
}
