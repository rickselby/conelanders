<?php

namespace App\Interfaces\RallyCross;

use App\Models\RallyCross\RxChampionship;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface ChampionshipInterface
{
    /**
     * Check if the current championship has results that are being shown before they're released
     *
     * @param RxChampionship $championship
     *
     * @return bool
     */
    public function shownBeforeRelease(RxChampionship $championship);

    /**
     * Get news on completed championships
     *
     * @return array
     */
    public function getPastNews(Carbon $start, Carbon $end);

    /**
     * Get a list of cars used in a given championship
     *
     * @param RxChampionship $championship
     *
     * @return Collection
     */
    public function cars(RxChampionship $championship);

    /**
     * Has the given championship got more than one car?
     *
     * @param RxChampionship $championship
     *
     * @return boolean
     */
    public function multipleCars(RxChampionship $championship);
}