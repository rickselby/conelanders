<?php

namespace App\Interfaces\RallyCross;

use App\Models\PointsSequence;
use App\Models\RallyCross\RxEvent;
use Carbon\Carbon;

interface EventInterface
{
    /**
     * Can we show the given event's results to the current user?
     *
     * @param RxEvent $event
     *
     * @return bool
     */
    public function canBeShown(RxEvent $event);

    /**
     * Check if the currently logged in user is part of the given event
     *
     * @param RxEvent $event
     *
     * @return bool
     */
    public function currentUserInEvent(RxEvent $event);

    /**
     * Get a list of driver IDs that are listed against a session for the given event
     *
     * @param RxEvent $event
     *
     * @return array
     */
    public function getDriverIDs(RxEvent $event);

    /**
     * Check if we have heat results yet
     *
     * @param RxEvent $event
     * @return int
     */
    public function hasHeatResults(RxEvent $event);

    /**
     * Check if all heats are marked as complete
     * @param RxEvent $event
     * @return bool
     */
    public function areHeatsComplete(RxEvent $event);

    /**
     * Check if we have points for the heats
     *
     * @param RxEvent $event
     * @return bool
     */
    public function hasHeatPoints(RxEvent $event);

    /**
     * Get heat results for the current event
     * @param RxEvent $event
     * @return mixed
     */
    public function getHeatResults(RxEvent $event);

    /**
     * Apply the given points sequence to the session results
     *
     * @param RxEvent $event
     * @param PointsSequence $sequence
     */
    public function applyHeatsPointsSequence(RxEvent $event, PointsSequence $sequence);

    /**
     * Set points for entrants to the given points
     *
     * @param RxEvent $event
     * @param [] $points Keyed array, entrantID => points
     */
    public function setHeatsPoints(RxEvent $event, $points);

    /**
     * Get news about events
     * @return mixed
     */
    public function getPastNews(Carbon $start, Carbon $end);
    
    /**
     * Get news about events
     * @return mixed
     */
    public function getUpcomingNews(Carbon $start, Carbon $end);
}