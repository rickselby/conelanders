<?php

namespace App\Models;

class Stage extends \Eloquent
{
    protected $fillable = ['name', 'order'];

    protected $casts = [
        'order' => 'integer'
    ];

    public function event()
    {
        return $this->hasOne(Event::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
