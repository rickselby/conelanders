<?php

namespace App\Interfaces\RallyCross;

interface ConstructorStandingsInterface extends StandingsInterface
{
    /**
     * Get the list of possible options for scoring this championship
     * @return mixed
     */
    public function getOptions();
}