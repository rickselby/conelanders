<?php

namespace App\Models\RallyCross;

class RxCar extends \Eloquent
{
    protected $fillable = ['name', 'short_name'];

    public function entrants()
    {
        return $this->hasMany(RxSessionEntrant::class);
    }
}
