<?php

namespace App\Services\DirtRally;

use App\Models\DirtRally\DirtEvent;
use Carbon\Carbon;

class Events
{
    public function getCurrent()
    {
        return DirtEvent::with('season.championship')
            ->where('opens', '<', Carbon::now())
            ->where('closes', '>', Carbon::now())
            ->get();
    }
}