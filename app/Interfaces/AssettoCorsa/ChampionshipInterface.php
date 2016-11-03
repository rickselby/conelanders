<?php

namespace App\Interfaces\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface ChampionshipInterface
{
    /**
     * Check if the current championship has results that are being shown before they're released
     *
     * @param AcChampionship $championship
     *
     * @return bool
     */
    public function shownBeforeRelease(AcChampionship $championship);

    /**
     * Get news on completed championships
     *
     * @return array
     */
    public function getPastNews(Carbon $start, Carbon $end);

    /**
     * Get a list of cars used in a given championship
     *
     * @param AcChampionship $championship
     *
     * @return Collection
     */
    public function cars(AcChampionship $championship);

    /**
     * Has the given championship got more than one car?
     *
     * @param AcChampionship $championship
     *
     * @return boolean
     */
    public function multipleCars(AcChampionship $championship);
}