<?php

namespace App\Models\DirtRally;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class DirtEvent extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name', 'opens', 'closes', 'racenet_event_id'];

    protected $dates = ['opens', 'closes', 'last_import'];

    protected $casts = [
        'racenet_event_id' => 'integer',
        'importing' => 'boolean',
    ];

    protected $sluggable = [
        'unique' => false,
    ];

    public function season()
    {
        return $this->belongsTo(DirtSeason::class, 'dirt_season_id');
    }

    public function stages()
    {
        return $this->hasMany(DirtStage::class)->orderBy('order');
    }

    public function positions()
    {
        return $this->hasMany(DirtEventPosition::class)->orderBy('position');
    }

    public function isComplete()
    {
        return $this->closes < $this->last_import;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getFullNameAttribute()
    {
        return $this->season->fullName.' - '.$this->name;
    }
}
