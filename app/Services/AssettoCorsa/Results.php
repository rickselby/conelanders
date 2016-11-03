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
    public function forRace(AcSession $session)
    {
        if ($session->type == AcSession::TYPE_RACE) {
            $firstEntrant = null;
            $lastEntrant = null;

            $raceEntrants = $session->entrants()->with(
                'car',
                'championshipEntrant.driver.nation',
                'championshipEntrant.team',
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
            'car',
            'championshipEntrant.driver.nation',
            'championshipEntrant.team',
            'fastestLap.sectors'
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
                    $points = \ACDriverStandings::championship($result->session->event->championship);
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
                    $points = \ACDriverStandings::event($result->session->event);
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
        $eventResult = \ACDriverStandings::eventSummary($event);
        foreach($eventResult AS $result) {
            if ($result['position'] == 1) {
                $winners[] = $result['entrant'];
            }
        }
        return $winners;
    }
}
