<?php

namespace App\Interfaces\Races;

use App\Models\Races\RacesChampionship;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface ChampionshipInterface
{
    /**
     * Check if the current championship has results that are being shown before they're released
     *
     * @param RacesChampionship $championship
     *
     * @return bool
     */
    public function shownBeforeRelease(RacesChampionship $championship);

    /**
     * Get news on completed championships
     *
     * @return array
     */
    public function getPastNews(Carbon $start, Carbon $end);

    /**
     * Get a list of cars used in a given championship
     *
     * @param RacesChampionship $championship
     *
     * @return Collection
     */
    public function cars(RacesChampionship $championship);

    /**
     * Has the given championship got more than one car?
     *
     * @param RacesChampionship $championship
     *
     * @return boolean
     */
    public function multipleCars(RacesChampionship $championship);
}