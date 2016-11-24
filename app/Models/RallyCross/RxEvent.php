<?php

namespace App\Models\RallyCross;

use Carbon\Carbon;
use Collective\Html\Eloquent\FormAccessible;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RxEvent extends \Eloquent
{
    use FormAccessible, Sluggable ;

    protected $fillable = ['name', 'time', 'release'];

    protected $dates = ['time', 'release'];

    public function championship()
    {
        return $this->belongsTo(RxChampionship::class, 'rx_championship_id');
    }

    public function sessions()
    {
        return $this->hasMany(RxSession::class)->orderBy('order');
    }

    public function entrants()
    {
        return $this->hasMany(RxEventEntrant::class);
    }

    public function heatResult()
    {
        return $this->hasMany(RxHeatResult::class);
    }

    public function getHeatsAttribute()
    {
        return $this->sessions->filter(function($session) {
            return $session->heat;
        });
    }

    public function getNotHeatsAttribute()
    {
        return $this->sessions->filter(function($session) {
            return !$session->heat;
        });
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
        return $this->release ?: $this->time;
    }

    public function formTimeAttribute()
    {
        return $this->time ? $this->time->format('jS F Y, H:i') : '';
    }

    public function formReleaseAttribute()
    {
        return $this->release ? $this->release->format('jS F Y, H:i') : '';
    }

    public function canBeReleased() 
    {
        // If there are no sessions, or one session cannot be shown,
        // then the event cannot be released either
        if (count($this->sessions)) {
            foreach($this->sessions AS $session) {
                if (!$session->show) {
                    return false;
                }
            }
        } else {
            return false;
        }

        return $this->release ? $this->release->lt(Carbon::now()) : false;
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
        return $query->where('rx_championship_id', $model->rx_championship_id);
    }    
    
    public function getRouteKeyName()
    {
        return 'slug';
    }


}
