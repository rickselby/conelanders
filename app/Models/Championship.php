<?php

namespace App\Models;

use Carbon\Carbon;

class Championship extends \Eloquent
{
    protected $fillable = ['name'];

    public function seasons()
    {
        // Can't sort at database level
        return $this->hasMany(Season::class);
    }

    public function getOpensAttribute()
    {
        $dates = [];
        foreach($this->seasons AS $season) {
            $dates[] = $season->opens;
        }
        if (count($dates)) {
            return min($dates);
        } else {
            // No seasons; push to bottom of list
            return Carbon::now();
        }
    }

    public function getClosesAttribute()
    {
        $dates = [];
        foreach($this->seasons AS $season) {
            $dates[] = $season->closes;
        }
        if (count($dates)) {
            return max($dates);
        } else {
            // No seasons; push to bottom of list
            return Carbon::now();
        }
    }
}
