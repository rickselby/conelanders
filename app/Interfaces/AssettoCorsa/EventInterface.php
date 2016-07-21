<?php

namespace App\Interfaces\AssettoCorsa;


use App\Models\AssettoCorsa\AcEvent;
use Carbon\Carbon;

interface EventInterface
{
    /**
     * Can we show the given event's results to the current user?
     *
     * @param AcEvent $event
     *
     * @return bool
     */
    public function canBeShown(AcEvent $event);

    /**
     * Check if the currently logged in user is part of the given event
     *
     * @param AcEvent $event
     *
     * @return bool
     */
    public function currentUserInEvent(AcEvent $event);

    /**
     * Get a list of driver IDs that are listed against a session for the given event
     *
     * @param AcEvent $event
     *
     * @return array
     */
    public function getDriverIDs(AcEvent $event);

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