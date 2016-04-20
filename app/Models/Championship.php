<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;


class Championship extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name'];

    protected $sluggify = [
        'build_from' => 'shortName'
    ];

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

    public function getShortNameAttribute()
    {
        return trim(str_ireplace('championship', '', $this->name));
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
