<?php

namespace App\Models\Races;

use App\Models\Playlist;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RacesSession extends \Eloquent
{
    const TYPE_PRACTICE = 1;
    const TYPE_QUALIFYING = 2;
    const TYPE_RACE = 3;

    use Sluggable;

    protected $fillable = ['name', 'order', 'type'];

    protected $dates = ['release'];

    protected $casts = [
        'importing' => 'boolean',
        'order' => 'integer',
        'type' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(RacesEvent::class, 'races_event_id');
    }

    public function entrants()
    {
        return $this->hasMany(RacesSessionEntrant::class);
    }

    public function playlist()
    {
        return $this->morphOne(Playlist::class, 'playlistable');
    }

    public function getFullNameAttribute()
    {
        return $this->event->fullName.' - '.$this->name;
    }

    public function getShortNameAttribute()
    {
        $shortName = trim(str_ireplace(['practice', 'qualifying', 'race'], '', $this->name));

        return $shortName ?: $this->name;
    }
    
    public function getCompleteAtAttribute()
    {
        return $this->release;
    }

    public function canBeReleased() {
        return !$this->importing
            && $this->release !== NULL
            && $this->release <= Carbon::now()
            ;
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
        return $query->where('races_event_id', $model->races_event_id);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    static public function getTypes()
    {
        return [
            self::TYPE_PRACTICE => 'Practice',
            self::TYPE_QUALIFYING => 'Qualifying',
            self::TYPE_RACE => 'Race',
        ];
    }
}
