<?php

namespace App\Services\Cached\Races;

use App\Models\Races\RacesChampionship;
use App\Models\Races\RacesEvent;
use App\Models\Races\RacesSession;
use App\Models\Driver;
use App\Interfaces\Races\ResultsInterface;
use Illuminate\Contracts\Cache\Repository;

class Results implements ResultsInterface
{
    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var \App\Services\Races\Results
     */
    protected $resultsService;

    /**
     * @var string
     */
    protected $cacheKey = 'races.results.';

    public function __construct(Repository $cache, \App\Services\Races\Results $resultsService)
    {
        $this->cache = $cache;
        $this->resultsService = $resultsService;
    }

    /**
     * {@inheritdoc}
     */
    public function fastestLaps(RacesSession $session)
    {
        // This doesn't rely on "is user in this session" or "is session released yet" so it can be cached permanently
        // (any updates to the session will clear the cache)
        return $this->cache->tags(\RacesCacheHandler::sessionTag($session))->rememberForever($this->cacheKey.'fastestLaps.'.$session->id, function() use ($session) {
            return $this->resultsService->fastestLaps($session);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function forDriver(Driver $driver)
    {
        // Find out the next time one of the drivers' championships will be updated
        $nextUpdate = false;
        foreach($driver->raceEntries AS $entry) {
            $champUpdate = $entry->championship->getNextUpdate();
            if ($champUpdate) {
                if ($nextUpdate) {
                    $nextUpdate = min($nextUpdate, $champUpdate);
                } else {
                    $nextUpdate = $champUpdate;
                }
            }
        }

        // Set up details for caching the info
        $key = \RacesCacheHandler::driverKey($driver);
        $function = function() use ($driver) {
            return $this->resultsService->forDriver($driver);
        };

        // Cache forever if all championships are complete; or until the next championship update if not
        if (!$nextUpdate) {
            return $this->cache->rememberForever($key, $function);
        } else {
            return $this->cache->remember($key, $nextUpdate, $function);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function forRace(RacesSession $session)
    {
        // This doesn't rely on "is user in this session" or "is session released yet" so it can be cached permanently
        // (any updates to the session will clear the cache)
        return $this->cache->tags(\RacesCacheHandler::sessionTag($session))->rememberForever($this->cacheKey.'forRace.'.$session->id, function() use ($session) {

            // Ooh, we're going to tidy this up before we cache it
            $results = $this->resultsService->forRace($session);
            // Clear the relations; we don't need the list of laps
            foreach($results AS $entrant) {
                $entrant->setRelations([])->load(
                    'car',
                    'championshipEntrant.driver.nation',
                    'championshipEntrant.team'
                );
            }
            return $results;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function lapChart(RacesSession $session)
    {
        // This doesn't rely on "is user in this session" or "is session released yet" so it can be cached permanently
        // (any updates to the session will clear the cache)
        return $this->cache->tags(\RacesCacheHandler::sessionTag($session))->rememberForever($this->cacheKey.'lapChart.'.$session->id, function() use ($session) {
            return $this->resultsService->lapChart($session);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getWinner(RacesEvent $event)
    {
        return $this->cache->tags(\RacesCacheHandler::eventTag($event))->rememberForever($this->cacheKey.'winner.'.$event->id, function() use ($event) {
            return $this->resultsService->getWinner($event);
        });
    }

}