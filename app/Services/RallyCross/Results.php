<?php

namespace App\Services\RallyCross;

use App\Models\RallyCross\RxChampionship;
use App\Models\RallyCross\RxEvent;
use App\Models\RallyCross\RxSession;
use App\Models\Driver;
use App\Interfaces\RallyCross\ResultsInterface;
use LapChart\Chart;

class Results implements ResultsInterface
{
    /**
     * {@inheritdoc}
     */
    public function forRace(RxSession $session)
    {
        $firstEntrant = null;
        $lastEntrant = null;

        $raceEntrants = $session->entrants->sortBy('position');

        foreach($raceEntrants AS $entrant) {

            if (!$firstEntrant) {
                $firstEntrant = $entrant;
            }

            // Set time behind first
            $entrant->timeBehindFirst = $entrant->totalTime - $firstEntrant->totalTime;

            // Set time behind car in front
            if ($lastEntrant) {
                $entrant->timeBehindAhead = $entrant->totalTime - $lastEntrant->totalTime;
            } else {
                $entrant->timeBehindAhead = null;
            }

            // Update last entrant
            $lastEntrant = $entrant;
        }

        return $raceEntrants;
    }

    /**
     * {@inheritdoc}
     */
    public function forDriver(Driver $driver)
    {
        $results['all'] = $this->getAllForDriver($driver);
        $results['best'] = $this->getBestFromAll($results['all']);

        return $results;
    }

    /**
     * @param Driver $driver
     * @return array
     */
    protected function getAllForDriver(Driver $driver)
    {
        $driver->load('rallyCrossResults.event.sessions.event.championship');

        $championships = [];

        foreach($driver->rallyCrossResults AS $entry) {
            foreach($entry->entries->sortBy(function($entry) {
                return $entry->session->event->time.'-'.$entry->session->order;
            }) AS $result) {

                $championshipID = $result->session->event->championship->id;
                $eventID = $result->session->event->id;
                $sessionID = $result->session->id;

                if (!isset($championships[$championshipID])) {
                    // Load back down the chain
                    $result->session->event->championship->load('events.sessions.entrants.eventEntrant.driver');
                    $points = \RXDriverStandings::championship($result->session->event->championship);
                    $points = array_where($points, function($key, $value) use ($driver) {
                        return $value['entrant']->id == $driver->id;
                    });
                    $driverPoints = array_pop($points);

                    $championships[$championshipID] = [
                        'championship' => $result->session->event->championship,
                        'position' => $result->session->event->championship->isComplete()
                            ? $driverPoints['position']
                            : NULL,
                        'events' => [],
                    ];
                    $result->session->event->championship->setRelations([]);
                }

                if (!isset($championships[$championshipID]['events'][$eventID])) {
                    $result->session->event->load('sessions.entrants.eventEntrant.driver');
                    $points = \RXDriverStandings::event($result->session->event);
                    $points = array_where($points, function($key, $value) use ($driver) {
                        return $value['entrant']->driver->id == $driver->id;
                    });
                    $eventResult = array_pop($points);

                    $championships[$championshipID]['events'][$eventID] = [
                        'event' => $result->session->event,
                        'position' => $result->session->event->canBeReleased()
                            ? $eventResult['position']
                            : NULL,
                        'sessions' => [],
                    ];
                }

                if ($result->session->event->canBeReleased()) {

                    $raceResults = \RXResults::forRace($result->session);

                    $raceResults = array_where($raceResults, function($key, $value) use ($driver) {
                        return $value->eventEntrant->driver->id == $driver->id;
                    });
                    $raceResult = array_pop($raceResults);
                    $raceResult->setRelations([]);

                    $championships[$championshipID]['events'][$eventID]['sessions'][$sessionID] = [
                        'session' => $result->session,
                        'position' => $result->position,
                        'result' => $raceResult,
                    ];
                }

                $result->session->event->setRelations([]);
                $result->session->setRelations([]);
            }
        }

        return $championships;
    }

    protected function getBestFromAll($results)
    {
        $bests = [
            'championship' => [],
            'event' => [],
            'race' => [],
        ];

        foreach($results AS $champID => $championship) {
            foreach($championship['events'] AS $eventID => $event) {
                foreach($event['sessions'] AS $sessionID => $session) {
                    $this->getBest($bests['race'], $session, function($a) {
                        return $a['result']->position;
                    }, function($a) {
                        return $a['session'];
                    });
                }
                $this->getBest($bests['event'], $event, function($a) {
                    return $a['position'];
                }, function($a) {
                    return $a['event'];
                });
            }

            $this->getBest($bests['championship'], $championship, function($a) {
                return $a['position'];
            }, function($a) {
                return $a['championship'];
            });
        }

        return $bests;
    }

    protected function getBest(&$current, $new, $getPosition, $filter)
    {
        $newPosition = $getPosition($new);

        if ($newPosition === NULL) {
            return;
        }

        if (!isset($current['best']) || $current['best'] > $newPosition) {
            $current['best'] = $newPosition;
            $current['things'] = collect([$filter($new)]);
        } elseif ($current['best'] == $newPosition) {
            $current['things']->push($filter($new));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getWinner(RxEvent $event)
    {
        $winners = [];
        $eventResult = \RXDriverStandings::eventSummary($event);
        foreach($eventResult AS $result) {
            if ($result['position'] == 1) {
                $winners[] = $result['entrant'];
            }
        }
        return $winners;
    }
}
