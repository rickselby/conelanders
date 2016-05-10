<?php

namespace App\Models\DirtRally;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class DirtStage extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name', 'order', 'long'];

    protected $casts = [
        'order' => 'integer',
        'long' => 'boolean',
    ];

    protected $sluggable = [
        'unique' => false,
    ];

    public function event()
    {
        return $this->belongsTo(DirtEvent::class, 'dirt_event_id');
    }

    public function results()
    {
        return $this->hasMany(DirtResult::class)->orderBy('position');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function getFullNameAttribute()
    {
        return $this->event->fullName.' - '.$this->name;
    }
}
