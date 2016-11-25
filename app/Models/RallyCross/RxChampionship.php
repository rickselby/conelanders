<?php

namespace App\Models\RallyCross;

use App\Models\User;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class RxChampionship extends \Eloquent
{
    use Sluggable, SluggableScopeHelpers;

    protected $fillable = [
        'name',
        'drop_events',
        'constructors_count',
    ];

    public function events()
    {
        return $this->hasMany(RxEvent::class)->orderBy('time');
    }

    public function admins()
    {
        return $this->belongsToMany(User::class, 'rx_championship_admins');
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
                $nextUpdate = $event->completeAt;
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

    public function scopeForUser($query, User $user)
    {
        return $query->leftJoin('rx_championship_admins', 'rx_championship_admins.rx_championship_id', '=', 'rx_championships.id')
            ->where('rx_championship_admins.user_id', '=', $user->id)
            ->select('rx_championships.*')
            ->distinct();
    }

}
