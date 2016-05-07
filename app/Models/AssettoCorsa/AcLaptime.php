<?php

namespace App\Models\AssettoCorsa;

class AcLaptime extends \Eloquent
{
    protected $fillable = ['time'];

    protected $casts = [
        'time' => 'integer',
    ];

    public function sectors()
    {
        return $this->hasMany(AcLaptimeSector::class);
    }
}
