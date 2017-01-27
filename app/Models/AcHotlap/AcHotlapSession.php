<?php

namespace App\Models\AcHotlap;

use App\Models\Races\RacesCar;
use Carbon\Carbon;
use Collective\Html\Eloquent\FormAccessible;
use Cviebrock\EloquentSluggable\Sluggable;

class AcHotlapSession extends \Eloquent
{
    use FormAccessible, Sluggable;

    protected $fillable = ['name', 'start', 'finish'];

    protected $dates = ['start', 'finish'];

    public function entrants()
    {
        return $this->hasMany(AcHotlapSessionEntrant::class);
    }

    public function cars()
    {
        return $this->belongsToMany(RacesCar::class, 'ac_hotlap_session_cars');
    }

    public function isComplete()
    {
        return $this->finish->lt(Carbon::now());
    }

    public function formStartAttribute()
    {
        return $this->start ? $this->start->format('jS F Y') : '';
    }

    public function formFinishAttribute()
    {
        return $this->finish ? $this->finish->format('jS F Y') : '';
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

    public function getRouteKeyName()
    {
        return 'slug';
    }

}
