<?php

namespace App\Models\RallyCross;

use App\Models\Driver;

class RxSessionEntrant extends \Eloquent
{
    protected $fillable = ['race', 'time', 'position', 'penalty', 'points'];

    protected $casts = [
        'time' => 'integer',
        'position' => 'integer',
        'penalty' => 'integer',
        'points' => 'integer',
    ];

    public function session()
    {
        return $this->belongsTo(RxSession::class, 'rx_session_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function car()
    {
        return $this->belongsTo(RxCar::class, 'rx_car_id');
    }
}
