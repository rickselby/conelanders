<?php

namespace App\Models\AssettoCorsa;

use App\Models\Driver;

class AcChampionshipEntrant extends \Eloquent
{
    protected $fillable = ['driver_id', 'rookie', 'number', 'colour'];

    protected $casts = [
        'rookie' => 'boolean',
    ];

    public function championship()
    {
        return $this->belongsTo(AcChampionship::class, 'ac_championship_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function entries()
    {
        return $this->hasMany(AcRaceEntrant::class);
    }
}
