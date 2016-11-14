<?php

namespace App\Models\RallyCross;

use App\Models\Playlist;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RxSession extends \Eloquent
{
    use Sluggable;

    protected $fillable = ['name', 'order', 'heat'];

    protected $casts = [
        'order' => 'integer',
        'heat' => 'boolean',
        'show' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(RxEvent::class, 'rx_event_id');
    }

    public function entrants()
    {
        return $this->hasMany(RxSessionEntrant::class);
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
    
    public function canBeReleased()
    {
        return $this->show;
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
        return $query->where('rx_event_id', $model->rx_event_id);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
