<?php

namespace App\Models\AssettoCorsa;

class AcLaptimeSector extends \Eloquent
{
    protected $fillable = ['sector', 'time'];

    protected $casts = [
        'sector' => 'integer',
        'time' => 'integer',
    ];

    public function lap()
    {
        return $this->belongsTo(AcLaptime::class, 'ac_laptime_id');
    }
}
