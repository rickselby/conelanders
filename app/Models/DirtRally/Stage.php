<?php

namespace App\Models\DirtRally;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Stage extends \Eloquent implements SluggableInterface
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
        return $this->belongsTo(Event::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class)->orderBy('position');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function getFullNameAttribute()
    {
        return $this->event->season->championship->name
            .' - '.$this->event->season->name
            .' - '.$this->event->name
            .' - '.$this->name;
    }
}
