<?php

namespace App\Models;

class Event extends \Eloquent
{
    protected $fillable = ['name', 'closes', 'dirt_id'];

    protected $dates = ['closes'];

    protected $casts = [
        'dirt_id' => 'integer',
    ];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function stages()
    {
        return $this->hasMany(Stage::class);
    }
}
