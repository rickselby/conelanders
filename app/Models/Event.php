<?php

namespace App\Models;

class Event extends \Eloquent
{
    protected $fillable = ['name', 'opens', 'closes', 'dirt_id'];

    protected $dates = ['opens', 'closes', 'last_import'];

    protected $casts = [
        'dirt_id' => 'integer',
        'importing' => 'boolean',
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
}
