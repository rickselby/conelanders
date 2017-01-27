<?php

namespace App\Models\AcHotlap;

use App\Models\Driver;
use App\Models\Races\RacesCar;

class AcHotlapSessionEntrant extends \Eloquent
{
    protected $fillable = ['time', 'sectors'];

    protected $casts = [
        'sectors' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(AcHotlapSession::class, 'ac_hotlap_session_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function car()
    {
        return $this->belongsTo(RacesCar::class, 'races_car_id');
    }
}
