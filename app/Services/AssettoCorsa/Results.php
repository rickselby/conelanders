<?php

namespace App\Services\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcEvent;
use App\Models\AssettoCorsa\AcSession;
use App\Models\Driver;
use App\Interfaces\AssettoCorsa\ResultsInterface;
use LapChart\Chart;

class Results implements ResultsInterface
{
    /**
     * {@inheritdoc}
     */
    public function eventSummary(AcEvent $event)
    {
        $results = [];

        foreach($event->sessions AS $session) {
            foreach($session->entrants AS $entrant) {
                $entrantID = $entrant->championshipEntrant->id;

                if (!isset($results[$entrantID])) {
                    $results[$entrantID] = [
                        'entrant' => $entrant->championshipEntrant,
                        'points' => 0,
                        'pointsList' => [],
                        'positions' => [],
                    ];
                }

                if (\ACSession::canBeShown($session)) {
                    $results[$entrantID]['sessionPoints'][$session->id] = $entrant->points + $entrant->fastest_lap_points;
                    $results[$entrantID]['points'] += $results[$entrantID]['sessionPoints'][$session->id];
                    $results[$entrantID]['pointsList'][] = $results[$entrantID]['sessionPoints'][$session->id];
                    rsort($results[$entrantID]['pointsList']);
                    if ($session->type == AcSession::TYPE_RACE) {
                        $results[$entrantID]['positions'][] = $entrant->position;
                        sort($results[$entrantID]['positions']);
                    }
                    $results[$entrantID]['sessionPositions'][$session->id] = $entrant->position;
                }
            }
        }

        usort($results, [$this, 'pointsSort']);

        $results = \Positions::addToArray($results, [$this, 'arePointsEqual']);

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function event(AcEvent $event)
    {
        $results = [];

        if (\ACEvent::canBeShown($event)) {
            foreach($event->sessions AS $session) {
                foreach($session->entrants AS $entrant) {
                    $entrantID = $entrant->championshipEntrant->id;

                    if (!isset($results[$entrantID])) {
                        $results[$entrantID] = [
                            'entrant' => $entrant->championshipEntrant,
                            'points' => 0,
                            'pointsList' => [],
                            'positions' => [],
                        ];
                    }
                    $results[$entrantID]['points'] += $entrant->points + $entrant->fastest_lap_points;
                    $results[$entrantID]['pointsList'][] = $entrant->points + $entrant->fastest_lap_points;
                    rsort($results[$entrantID]['pointsList']);
                    if ($session->type == AcSession::TYPE_RACE) {
                        $results[$entrantID]['positions'][] = $entrant->position;
                        sort($results[$entrantID]['positions']);
                    }
                }
            }
        }

        usort($results, [$this, 'pointsSort']);
        
        $results = \Positions::addToArray($results, [$this, 'arePointsEqual']);

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function championship(AcChampionship $championship)
    {
        $championship->load('events.sessions.entrants.championshipEntrant.driver.nation');
        $results = [];
        foreach($championship->entrants AS $entrant) {
            $results[$entrant->id]['entrant'] = $entrant;
            $results[$entrant->id]['points'] = 0;
            $results[$entrant->id]['pointsList'] = [];
            $results[$entrant->id]['eventPoints'] = [];
            $results[$entrant->id]['positions'] = [];
            $results[$entrant->id]['eventPositions'] = [];
            $results[$entrant->id]['dropped'] = [];
        }
        foreach($championship->events AS $event) {
            $eventResults = \ACResults::event($event);
            $eventResultsWithEquals = \Positions::addEquals($eventResults);
            foreach ($eventResults AS $key => $result) {
                $entrantID = $result['entrant']->id;
                $results[$entrantID]['points'] += $result['points'];
                $results[$entrantID]['pointsList'][] = $result['points'];
                $results[$entrantID]['eventPoints'][$event->id] = $result['points'];
                $results[$entrantID]['positions'][] = $result['position'];
                $results[$entrantID]['eventPositions'][$event->id] = $eventResultsWithEquals[$key]['position'];
                rsort($results[$entrantID]['pointsList']);
                sort($results[$entrantID]['positions']);
            }
        }

        // When the championship is complete, hide any drivers that have no results
        if ($championship->isComplete()) {
            foreach ($results AS $key => $info) {
                if (count($info['positions']) == 0) {
                    unset($results[$key]);
                }
            }
        }

        $results = $this->dropEvents($championship, $results);
        usort($results, [$this, 'pointsSort']);
        $results = \Positions::addToArray($results, [$this, 'arePointsEqual']);

        return $results;
    }

    /**
     * See if any events need dropping from the total points
     * @param AcChampionship $championship
     * @param $results
     * @return []
     */
    private function dropEvents(AcChampionship $championship, $results)
    {
        $dropEvents = $shownEvents = 0;

        // First, calculate how many dropped events to show
        if ($championship->drop_events != 0) {

            // we need to know how many events are available
            $totalEvents = $shownEvents = 0;
            foreach($championship->events AS $event) {
                $totalEvents++;
                if (\ACEvent::canBeShown($event)) {
                    $shownEvents++;
                }
            }

            // then work out how many dropped events should be shown
            // for 1, show it half way through
            // for 2, show one after a third, and the 2nd after 2/3rds
            // etc
            for ($i = 1; $i <= $championship->drop_events; $i++) {
                if ($shownEvents >= (($i / ($championship->drop_events + 1)) * $totalEvents)) {
                    $dropEvents++;
                }
            }
        }

        if ($dropEvents != 0) {
            // how many events can count?
            $countEvents = $shownEvents - $dropEvents;

            // Work through each entrant
            foreach($results AS $entrantID => $result) {

                // Do we need to drop any events?
                if (count($result['eventPoints']) > $countEvents) {

                    // Get event IDs beyond the number of events to count
                    $eventsToDrop = array_slice($this->getDropEventIDsSorted($result), $countEvents);

                    // Step through the events to drop
                    foreach($eventsToDrop AS $eventID) {
                        // Remove the points
                        $results[$entrantID]['points'] -= $result['eventPoints'][$eventID];
                        // Mark that this event was dropped
                        $results[$entrantID]['dropped'][] = $eventID;
                    }
                }

            }
        }
        return $results;
    }

    /**
     * Sort championship results by points (descending) and position (ascending) and return the event IDs in that order
     * @param [] $result
     * @return int[]
     */
    private function getDropEventIDsSorted($result)
    {
        // Build an array of points and positions
        $list = [];
        foreach($result['eventPoints'] AS $event => $points) {
            $list[$event] = [
                'points' => $points,
                'position' => $result['eventPositions'][$event],
            ];
        }
        // Sort it
        uasort($list, function($a, $b) {
            if ($a['points'] == $b['points']) {
                // Points are the same; drop the higher numbered position
                return $a['position'] - $b['position'];
            } else {
                return $b['points'] - $a['points'];
            }
        });
        return array_keys($list);
    }

    /**
     * Check two event results to see if they are equal
     * @param $a
     * @param $b
     * @return bool
     */
    public function arePointsEqual($a, $b)
    {
        return ($a['points'] == $b['points'])
            && ($a['positions'] == $b['positions'])
            && ($a['pointsList'] == $b['pointsList']);
    }

    /**
     * Sort overall points
     * @param mixed $a
     * @param mixed $b
     * @return int
     */
    private function pointsSort($a, $b)
    {
        if ($a['points'] != $b['points']) {
            return $b['points'] > $a['points'] ? 1 : -1;
        }

        // Then, best finishing positions; all the way down...
        for($i = 0; $i < max(count($a['positions']), count($b['positions'])); $i++) {
            // Check both have a position set
            if (isset($a['positions'][$i]) && isset($b['positions'][$i])) {
                // If they're different, compare them
                // If not, loop again
                if ($a['positions'][$i] != $b['positions'][$i]) {
                    return $a['positions'][$i] - $b['positions'][$i];
                }
            } elseif (isset($a['positions'][$i])) {
                // $a has less results; $b takes priority
                return -1;
            } elseif (isset($b['positions'][$i])) {
                // $b has less results; $a takes priority
                return 1;
            }
        }

        // So, the drivers have the same positions. So, let's see if they
        // Then, best points; all the way down...
        for($i = 0; $i < max(count($a['pointsList']), count($b['pointsList'])); $i++) {
            // Check both have a position set
            if (isset($a['pointsList'][$i]) && isset($b['pointsList'][$i])) {
                // If they're different, compare them
                // If not, loop again
                if ($a['pointsList'][$i] != $b['pointsList'][$i]) {
                    return $b['pointsList'][$i] - $a['pointsList'][$i];
                }
            } elseif (isset($a['pointsList'][$i])) {
                // $a has less results; $b takes priority
                return -1;
            } elseif (isset($b['pointsList'][$i])) {
                // $b has less results; $a takes priority
                return 1;
            }
        }

        // There's nothing more we can do to separate these drivers!
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function forRace(AcSession $session)
    {
        if ($session->type == AcSession::TYPE_RACE) {
            $firstEntrant = null;
            $lastEntrant = null;

            $raceEntrants = $session->entrants()->with(
                'championshipEntrant.driver.nation',
                'laps'
            )->orderBy('position')->get();

            foreach($raceEntrants AS $entrant) {
                if (!$firstEntrant) {
                    $firstEntrant = $entrant;
                }
                // Get positions gained
                $entrant->positionsGained = $entrant->started - $entrant->position;
                // Get laps behind first
                $entrant->lapsBehindFirst = count($firstEntrant->laps) - count($entrant->laps);
                // Set time behind first
                $entrant->timeBehindFirst = $entrant->time - $firstEntrant->time;

                // Set time behind car in front
                if ($lastEntrant) {
                    $entrant->timeBehindAhead = $entrant->time - $lastEntrant->time;
                } else {
                    $entrant->timeBehindAhead = null;
                }

                // Update last entrant
                $lastEntrant = $entrant;
            }
            return $raceEntrants;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fastestLaps(AcSession $session)
    {
        $firstEntrant = null;
        $lastEntrant = null;
        $sectors = [];
        
        $fastestLaps = $session->entrants()->with(
            'championshipEntrant.driver.nation',
            'fastestLap.sectors',
            'laps'
        )->orderBy('fastest_lap_position')->get();

        foreach($fastestLaps AS $entrant) {
            if (!$firstEntrant) {
                $firstEntrant = $entrant;
            }

            if ($entrant->fastestLap) {
                $entrant->timeBehindFirst = $entrant->fastestLap->time - $firstEntrant->fastestLap->time;

                // Set time behind car in front
                if ($lastEntrant && $lastEntrant->fastestLap) {
                    $entrant->timeBehindAhead = $entrant->fastestLap->time - $lastEntrant->fastestLap->time;
                } else {
                    $entrant->timeBehindAhead = null;
                }

                // save sector times...
                foreach($entrant->fastestLap->sectors AS $sector) {
                    $sectors[$sector->sector][$entrant->id]['time'] = $sector->time;
                }
                $entrant->sectorPosition = [];
            } else {
                $entrant->timeBehindFirst = null;
                $entrant->timeBehindAhead = null;
            }

            // Update last entrant
            $lastEntrant = $entrant;
        }

        $sectorPositions = [];
        // Sort out sector positions
        foreach($sectors AS $sector => $times) {
            asort($times);

            $times = \Positions::addToArray($times, function($a, $b) {
                return $a['time'] == $b['time'];
            });

            foreach($times AS $entrantID => $detail) {
                $sectorPositions[$entrantID][$sector] = $detail['position'];
            }
        }

        foreach($fastestLaps AS $entrant) {
            if (isset($sectorPositions[$entrant->id])) {
                $entrant->sectorPosition = $sectorPositions[$entrant->id];
            } else {
                $entrant->sectorPosition = [];
            }
        }

        return $fastestLaps;
    }

    /**
     * {@inheritdoc}
     */
    public function lapChart(AcSession $session)
    {
        $entrantLapCount = [];
        $entrantPositions = [];
        $lapped = [0];

        // Build the lap chart
        $laps = [];
        foreach($session->entrants AS $entrant) {
            foreach($entrant->laps AS $raceLap) {
                $laps[] = [
                    'time' => $raceLap->time,
                    'entrant' => $entrant,
                ];
            }
            $entrantLapCount[$entrant->championshipEntrant->driver->id] = 0;
            $entrantPositions[$entrant->championshipEntrant->driver->id] = [$entrant->started];
        }

        usort($laps, function($a, $b) {
            return $a['time'] - $b['time'];
        });

        $lapOrder = [];
        foreach($laps AS $lap) {
            $lapNumber = ++$entrantLapCount[$lap['entrant']->championshipEntrant->driver->id];
            if (!isset($lapOrder[$lapNumber])) {
                $lapOrder[$lapNumber] = 0;
                if ($lapNumber > 1) {
                    $lapped[$lapNumber - 1] = count($entrantLapCount) - $lapOrder[$lapNumber - 1];
                }
            }
            $position = ++$lapOrder[$lapNumber];

            $entrantPositions[$lap['entrant']->championshipEntrant->driver->id][] = $position;
        }

        $chart = new Chart();
        foreach($session->entrants AS $entrant) {
            $colour = $entrant->championshipEntrant->colour2
                ? [$entrant->championshipEntrant->colour, $entrant->championshipEntrant->colour2]
                : $entrant->championshipEntrant->colour;
            $chart->setDriver(
                $entrant->championshipEntrant->driver->name,
                $colour,
                $entrantPositions[$entrant->championshipEntrant->driver->id]
            );
        }
        $chart->setLapped($lapped);

        return $chart->generate();
    }

    /**
     * {@inheritdoc}
     */
    public function forDriver(Driver $driver)
    {
        $results['all'] = $this->getAllForDriver($driver);
        $results['best'] = $this->getBestForDriver($results['all']);

        return $results;
    }

    /**
     * @param Driver $driver
     * @return array
     */
    protected function getAllForDriver(Driver $driver)
    {
        $driver->load('acEntries.entries.session.event.championship');

        $championships = [];

        foreach($driver->acEntries AS $entry) {
            foreach($entry->entries->sortBy(function($entry) {
                return $entry->session->event->time.'-'.$entry->session->order;
            }) AS $result) {

                $championshipID = $result->session->event->championship->id;
                $eventID = $result->session->event->id;
                $sessionID = $result->session->id;

                if (!isset($championships[$championshipID])) {
                    // Load back down the chain
                    $result->session->event->championship->load('entrants.driver', 'events.sessions.entrants.championshipEntrant.driver');
                    $points = \ACResults::championship($result->session->event->championship);
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
                }

                if (!isset($championships[$championshipID]['events'][$eventID])) {
                    $result->session->event->load('sessions.entrants.championshipEntrant.driver');
                    $points = \ACResults::event($result->session->event);
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

                    $fastLapResults = \ACResults::fastestLaps($result->session);

                    $fastLapResults = array_where($fastLapResults, function($key, $value) use ($driver) {
                        return $value->championshipEntrant->driver->id == $driver->id;
                    });
                    $fastLapResult = array_pop($fastLapResults);

                    $championships[$championshipID]['events'][$eventID]['sessions'][$sessionID] = [
                        'session' => $result->session,
                        'position' => $result->position,
                        'result' => $fastLapResult
                    ];

                    if ($result->session->type == AcSession::TYPE_RACE) {
                        $raceResults = \ACResults::forRace($result->session);

                        $raceResults = array_where($raceResults, function($key, $value) use ($driver) {
                            return $value->championshipEntrant->driver->id == $driver->id;
                        });
                        $raceResult = array_pop($raceResults);

                        $championships[$championshipID]['events'][$eventID]['sessions'][$sessionID] = [
                            'session' => $result->session,
                            'position' => $result->position,
                            'result' => $raceResult,
                            'fastestLap' => $championships[$championshipID]['events'][$eventID]['sessions'][$sessionID],
                        ];
                    }
                }
            }
        }

        return $championships;
    }

    protected function getBestForDriver($results)
    {
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
                        case AcSession::TYPE_PRACTICE:
                            $this->getBest($bests['practice'], $session, function($a) {
                                return $a['result']->position;
                            });
                            break;
                        case AcSession::TYPE_QUALIFYING:
                            $this->getBest($bests['qualifying'], $session, function($a) {
                                return $a['result']->position;
                            });
                            break;
                        case AcSession::TYPE_RACE:
                            $this->getBest($bests['race'], $session, function($a) {
                                return $a['result']->position;
                            });
                            $this->getBest($bests['raceLap'], $session, function($a) {
                                return $a['fastestLap']['position'];
                            });
                            break;

                    }
                }
                $this->getBest($bests['event'], $event, function($a) {
                    return $a['position'];
                });
            }

            $this->getBest($bests['championship'], $championship, function($a) {
                return $a['position'];
            });
        }

        return $bests;
    }

    protected function getBest(&$current, $new, $getPosition)
    {
        $newPosition = $getPosition($new);

        if ($newPosition === NULL) {
            return;
        }

        if (!isset($current['best']) || $current['best'] > $newPosition) {
            $current['best'] = $newPosition;
            $current['things'] = collect([$new]);
        } elseif ($current['best'] == $newPosition) {
            $current['things']->push($new);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getWinner(AcEvent $event)
    {
        $winners = [];
        $eventResult = \ACResults::eventSummary($event);
        foreach($eventResult AS $result) {
            if ($result['position'] == 1) {
                $winners[] = $result['entrant'];
            }
        }
        return $winners;
    }
}
