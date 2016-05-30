<?php

namespace App\Models\AssettoCorsa;

use App\Models\PointsSequence;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;


class AcChampionship extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name', 'qual_points_sequence', 'race_points_sequence', 'laps_points_sequence'];

    protected $sluggable = [
        'build_from' => 'shortName'
    ];

    public function events()
    {
        return $this->hasMany(AcEvent::class)->orderBy('time');
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
        foreach($this->events AS $event) {
            $dates[] = max($event->time, $event->release);
        }
        return count($dates) ? max($dates) : Carbon::now();
    }

    public function isComplete()
    {
        foreach($this->events AS $event) {
            if (!$event->canBeReleased()) {
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
