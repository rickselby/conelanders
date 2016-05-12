<?php

namespace App\Models\DirtRally;

use Cviebrock\EloquentSluggable\SluggableInterface;
use RickSelby\EloquentSluggableKeyed\SluggableKeyedTrait;

class DirtStage extends \Eloquent implements SluggableInterface
{
    use SluggableKeyedTrait;

    protected $fillable = ['name', 'order', 'long'];

    protected $casts = [
        'order' => 'integer',
        'long' => 'boolean',
    ];

    protected $sluggable = [
        'unique_key' => 'dirt_event_id',
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
