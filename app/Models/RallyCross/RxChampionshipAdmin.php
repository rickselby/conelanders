<?php

namespace App\Models\RallyCross;

class RxChampionshipAdmin extends \Eloquent
{
    public function championship()
    {
        return $this->belongsTo(RxChampionship::class, 'rx_championship_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
