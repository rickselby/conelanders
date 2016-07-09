<?php

namespace App\Models\AssettoCorsa;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class AcChampionship extends \Eloquent
{
    use Sluggable, SluggableScopeHelpers;

    protected $fillable = ['name', 'drop_events'];

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
