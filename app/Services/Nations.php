<?php

namespace App\Services;

use App\Models\Nation;

class Nations
{
    public function findOrAdd($dirt_reference)
    {
        $nation = Nation::where('dirt_reference', $dirt_reference)->first();
        if ($nation->exists) {
            return $nation;
        } else {
            $nation = Nation::create(['dirt_reference' => $dirt_reference]);
            // TODO: Notify me that a new nation has been added and needs naming
            return $nation;
        }
    }
}
