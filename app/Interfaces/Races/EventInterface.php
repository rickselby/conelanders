<?php

namespace App\Interfaces\Races;

use App\Models\Races\RacesEvent;
use Carbon\Carbon;

interface EventInterface
{
    /**
     * Can we show the given event's results to the current user?
     *
     * @param RacesEvent $event
     *
     * @return bool
     */
    public function canBeShown(RacesEvent $event);

    /**
     * Check if the currently logged in user is part of the given event
     *
     * @param RacesEvent $event
     *
     * @return bool
     */
    public function currentUserInEvent(RacesEvent $event);

    /**
     * Get a list of driver IDs that are listed against a session for the given event
     *
     * @param RacesEvent $event
     *
     * @return array
     */
    public function getDriverIDs(RacesEvent $event);

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