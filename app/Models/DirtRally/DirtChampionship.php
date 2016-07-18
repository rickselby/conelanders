<?php

namespace App\Models\DirtRally;

use App\Models\PointsSequence;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class DirtChampionship extends \Eloquent
{
    use Sluggable, SluggableScopeHelpers;

    protected $fillable = ['name', 'event_points_sequence', 'stage_points_sequence'];

    protected $orderedSeasons;

    public function seasons()
    {
        // Can't sort at database level
        return $this->hasMany(DirtSeason::class);
    }

    public function eventPointsSequence()
    {
        return $this->belongsTo(PointsSequence::class, 'event_points_sequence');
    }

    public function stagePointsSequence()
    {
        return $this->belongsTo(PointsSequence::class, 'stage_points_sequence');
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


    public function getCompleteAtAttribute()
    {
        $dates = [];
        
        foreach($this->seasons AS $season) {
            if (!$season->isComplete()) {
                return null;
            } else {
                $dates[] = $season->completeAt;
            }
        }
        return max($dates);
    }

    public function isComplete()
    {
        if (count($this->seasons())) {
            foreach ($this->seasons AS $season) {
                if (!$season->isComplete()) {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function getOrderedSeasons()
    {
        if (!isset($this->orderedSeasons)) {
            $this->orderedSeasons = $this->seasons()->get()->sortBy('closes');
        }
        return $this->orderedSeasons;
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
