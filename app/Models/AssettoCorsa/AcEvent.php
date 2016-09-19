<?php

namespace App\Models\AssettoCorsa;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AcEvent extends \Eloquent
{
    use Sluggable;

    protected $fillable = ['name', 'time', 'signup_open', 'signup_close'];

    protected $dates = ['time', 'signup_open', 'signup_close'];

    public function championship()
    {
        return $this->belongsTo(AcChampionship::class, 'ac_championship_id');
    }

    public function sessions()
    {
        return $this->hasMany(AcSession::class)->orderBy('order');
    }

    public function signups()
    {
        return $this->hasMany(AcEventSignup::class);
    }

    public function getFullNameAttribute()
    {
        return $this->championship->name.' - '.$this->name;
    }

    public function getShortNameAttribute()
    {
        $words = explode(' ', $this->name);

        // Grab first three letters of the first name
        $shortName = mb_substr($words[0], 0, 3);

        if (count($words) > 1) {
            for ($i = 1; $i < count($words); $i++) {
                $shortName .= ' '.substr($words[$i], 0, 1);
            }
        }

        return mb_strtoupper($shortName);
    }

    public function getCompleteAtAttribute()
    {
        $dates = [];

        foreach($this->sessions AS $session) {
            if (!$session->completeAt) {
                return null;
            } else {
                $dates[] = $session->completeAt;
            }
        }
        return count($dates) ? max($dates) : null;
    }

    public function canBeReleased() 
    {
        if (count($this->sessions)) {
            foreach ($this->sessions AS $session) {
                if (!$session->canBeReleased()) {
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
    }
    
    public function countReleasedSessions()
    {
        $count = 0;
        foreach($this->sessions AS $session) {
            if ($session->canBeReleased()) {
                $count++;
            }
        }
        return $count;
    }

    public function getNextUpdate()
    {
        if (!$this->canBeReleased()) {
            foreach ($this->sessions AS $session) {
                if ($session->release > Carbon::now()) {
                    if (!isset($nextDate)) {
                        $nextDate = $session->release;
                    } else {
                        $nextDate = min($nextDate, $session->release);
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
     * Scope events to those where signup is open
     *
     * @param Builder $query
     * @param Model $model
     * @return mixed
     */
    public function scopeSignupOpen(Builder $query)
    {
        return $query->whereDate('signup_open', '<', Carbon::now())
            ->whereDate('signup_close', '>', Carbon::now());
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
                'source' => 'name',
            ]
        ];
    }

    /**
     * Sluggable configuration
     * Make the slugs unique based on the championship id
     */
    public function scopeWithUniqueSlugConstraints(Builder $query, Model $model, $attribute, $config, $slug)
    {
        return $query->where('ac_championship_id', $model->ac_championship_id);
    }    
    
    public function getRouteKeyName()
    {
        return 'slug';
    }


}
