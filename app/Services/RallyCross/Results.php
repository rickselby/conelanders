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
        /*
        foreach(RacesCategory::all() AS $category) {
            $results[$category->id]['all'] = $this->getAllForDriver($category, $driver);
            $results[$category->id]['best'] = $this->getBestFromAll($results[$category->id]['all']);
        }

        return $results;
        */
    }

    /**
     * @param Driver $driver
     * @return array
     */
    protected function getAllForDriver(Driver $driver)
    {
        /*
        $driver->load('raceEntries.entries.session.event.championship');

        $championships = [];

        foreach($driver->raceEntries()->forCategory($category)->get() AS $entry) {
            foreach($entry->entries->sortBy(function($entry) {
                return $entry->session->event->time.'-'.$entry->session->order;
            }) AS $result) {

                $championshipID = $result->session->event->championship->id;
                $eventID = $result->session->event->id;
                $sessionID = $result->session->id;

                if (!isset($championships[$championshipID])) {
                    // Load back down the chain
                    $result->session->event->championship->load('entrants.driver', 'events.sessions.entrants.championshipEntrant.driver');
                    $points = \RXDriverStandings::championship($result->session->event->championship);
                    $points = array_where($points, function($key, $value) use ($driver) {
                        return $value['entrant']->driver->id == $driver->id;
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
                    $result->session->event->load('sessions.entrants.championshipEntrant.driver');
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

                    $fastLapResults = \RXResults::fastestLaps($result->session);

                    $fastLapResults = array_where($fastLapResults, function($key, $value) use ($driver) {
                        return $value->championshipEntrant->driver->id == $driver->id;
                    });
                    $fastLapResult = array_pop($fastLapResults);
                    $fastLapResult->setRelations([]);

                    $championships[$championshipID]['events'][$eventID]['sessions'][$sessionID] = [
                        'session' => $result->session,
                        'position' => $result->position,
                        'result' => $fastLapResult
                    ];

                    if ($result->session->type == RxSession::TYPE_RACE) {
                        $raceResults = \RacesResults::forRace($result->session);

                        $raceResults = array_where($raceResults, function($key, $value) use ($driver) {
                            return $value->championshipEntrant->driver->id == $driver->id;
                        });
                        $raceResult = array_pop($raceResults);
                        $raceResult->setRelations([]);

                        $championships[$championshipID]['events'][$eventID]['sessions'][$sessionID] = [
                            'session' => $result->session,
                            'position' => $result->position,
                            'result' => $raceResult,
                            'fastestLap' => $championships[$championshipID]['events'][$eventID]['sessions'][$sessionID],
                        ];
                        unset(
                            $championships[$championshipID]['events'][$eventID]['sessions'][$sessionID]['fastestLap']['session']
                        );
                    }
                }

                $result->session->event->setRelations([]);
                $result->session->setRelations([]);
            }
        }

        return $championships;
        */
    }

    protected function getBestFromAll($results)
    {
        /*
        $bests = [
            'championship' => [],
            'event' => [],
            'practice' => [],
            'qualifying' => [],
            'race' => [],
            'raceLap' => [],
        ];

        foreach($results AS $champID => $championship) {
            foreach($championship['events'] AS $eventID => $event) {
                foreach($event['sessions'] AS $sessionID => $session) {
                    switch($session['session']->type) {
                        case RacesSession::TYPE_PRACTICE:
                            $this->getBest($bests['practice'], $session, function($a) {
                                return $a['result']->position;
                            }, function($a) {
                                return $a['session'];
                            });
                            break;
                        case RacesSession::TYPE_QUALIFYING:
                            $this->getBest($bests['qualifying'], $session, function($a) {
                                return $a['result']->position;
                            }, function($a) {
                                return $a['session'];
                            });
                            break;
                        case RacesSession::TYPE_RACE:
                            $this->getBest($bests['race'], $session, function($a) {
                                return $a['result']->position;
                            }, function($a) {
                                return $a['session'];
                            });
                            $this->getBest($bests['raceLap'], $session, function($a) {
                                return $a['fastestLap']['position'];
                            }, function($a) {
                                return $a['session'];
                            });
                            break;

                    }
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
        */
    }

    protected function getBest(&$current, $new, $getPosition, $filter)
    {
        /*
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
        */
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
