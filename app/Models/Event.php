<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Event extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name', 'opens', 'closes', 'dirt_id'];

    protected $dates = ['opens', 'closes', 'last_import'];

    protected $casts = [
        'dirt_id' => 'integer',
        'importing' => 'boolean',
    ];

    protected $sluggable = [
        'unique' => false,
    ];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function stages()
    {
        return $this->hasMany(Stage::class)->orderBy('order');
    }

    public function positions()
    {
        return $this->hasMany(EventPosition::class)->orderBy('position');
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
        return $this->season->championship->name
            .' - '.$this->season->name
            .' - '.$this->name;
    }
}
