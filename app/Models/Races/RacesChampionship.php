<?php

namespace App\Models\Races;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class RacesChampionship extends \Eloquent
{
    use Sluggable, SluggableScopeHelpers;

    protected $fillable = [
        'name',
        'drop_events',
        'constructors_count',
        'teams_count',
    ];

    public function events()
    {
        return $this->hasMany(RacesEvent::class)->orderBy('time');
    }

    public function entrants()
    {
        return $this->hasMany(RacesChampionshipEntrant::class);
    }

    public function teams()
    {
        return $this->hasMany(RacesTeam::class);
    }

    public function getShortNameAttribute()
    {
        return trim(str_ireplace('championship', '', $this->name));
    }

    public function getCompleteAtAttribute()
    {
        $dates = [];

        foreach($this->events AS $event) {
            if (!$event->completeAt) {
                return null;
            } else {
                $dates[] = $event->completeAt;
            }
        }
        return max($dates);
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
        if (count($this->events)) {
            foreach ($this->events AS $event) {
                if (!$event->canBeReleased()) {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function getNextUpdate() 
    {
        if (!$this->isComplete()) {
            foreach ($this->events AS $event) {
                $nextUpdate = $event->getNextUpdate();
                if ($nextUpdate) {
                    if (!isset($nextDate)) {
                        $nextDate = $nextUpdate;
                    } else {
                        $nextDate = min($nextDate, $nextUpdate);
                    }
                }
            }
            if (isset($nextDate)) {
                return $nextDate;
            }
        }
        return false;        
    }

    public function getNoTeamEntrantsSortedAttribute()
    {
        return $this->entrants()->with('driver.nation', 'car', 'team')->noTeam()->orderByNumber()->get();
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
                'source' => 'shortName',
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

}
