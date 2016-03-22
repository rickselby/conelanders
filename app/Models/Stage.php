<?php

namespace App\Models;

class Stage extends \Eloquent
{
    protected $fillable = ['name', 'order', 'long'];

    protected $casts = [
        'order' => 'integer',
        'long' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class)->orderBy('position');
    }
}
