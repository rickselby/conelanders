<?php

namespace App\Models\Races;

use App\Models\User;
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
        'teams_group_by_size',
    ];

    protected $casts = [
        'teams_group_by_size' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(RacesCategory::class, 'races_category_id');
    }

    public function admins()
    {
        return $this->morphToMany(User::class, 'adminable');
    }

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

        if (count($dates)) {
            return max($dates);
        } else {
            return null;
        }
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
     * Check if the given user is an admin of this championship
     *
     * @param User $user
     * @return mixed
     */
    public function isAdmin(User $user)
    {
        return $this->admins->contains($user);
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
