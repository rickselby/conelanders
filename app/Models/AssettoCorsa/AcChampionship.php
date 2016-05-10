<?php

namespace App\Models\AssettoCorsa;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;


class AcChampionship extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name'];

    protected $sluggable = [
        'build_from' => 'shortName'
    ];

    public function races()
    {
        return $this->hasMany(AcRace::class)->orderBy('time');
    }

    public function entrants()
    {
        return $this->hasMany(AcChampionshipEntrant::class);
    }

    public function getShortNameAttribute()
    {
        return trim(str_ireplace('championship', '', $this->name));
    }

    public function getEndsAttribute()
    {
        $dates = [];
        foreach($this->races AS $race) {
            $dates[] = max($race->time, $race->release);
        }
        return count($dates) ? max($dates) : Carbon::now();
    }

    public function isComplete()
    {
        foreach($this->races AS $race) {
            if (!$race->canBeReleased()) {
                return false;
            }
        }
        return true;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

}
