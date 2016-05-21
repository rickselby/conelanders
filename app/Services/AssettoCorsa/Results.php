<?php

namespace App\Services\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcPointsSystem;
use App\Models\AssettoCorsa\AcRace;
use App\Models\Driver;
use LapChart\Chart;

class Results
{
    public function hasQualifying(AcRace $race)
    {
        foreach($race->entrants AS $entrant) {
            if ($entrant->qualifying_position) {
                return true;
            }
        }

        return false;
    }

    public function qualifying(AcRace $race)
    {
        $results = [];
        $bestTime = 0;
        $lastTime = 0;
        foreach($race->entrants()->with('qualifyingLap.sectors', 'championshipEntrant.driver.nation')->orderBy('qualifying_position')->get() AS $entrant) {

            if ($entrant->qualifyingLap) {
                if ($bestTime) {
                    $gapToBest = $entrant->qualifyingLap->time - $bestTime;
                } else {
                    $gapToBest = null;
                    $bestTime = $entrant->qualifyingLap->time;
                }

                if ($lastTime) {
                    $gapToLast = $entrant->qualifyingLap->time - $lastTime;
                } else {
                    $gapToLast = 0;
                }
                $lastTime = $entrant->qualifyingLap->time;

                $sectors = [];
                foreach ($entrant->qualifyingLap->sectors AS $sector) {
                    $sectors[$sector->sector] = $sector->time;
                }

            } else {
                $gapToBest = null;
                $gapToLast = null;
                $sectors = [];
            }

            $results[] = [
                'position' => $entrant->qualifying_position,
                'driver' => $entrant->championshipEntrant->driver,
                'colour' => $entrant->championshipEntrant->colour,
                'colour2' => $entrant->championshipEntrant->colour2,
                'number' => $entrant->championshipEntrant->number,
                'rookie' => $entrant->championshipEntrant->rookie,
                'car' => $entrant->car,
                'lap' => $entrant->qualifyingLap,
                'toBest' => $gapToBest,
                'toLast' => $gapToLast,
                'lapSectors' => $sectors,
                'sectorPosition' => [],
            ];
        }

        // Sort by each sector
        if (count($results)) {
            foreach (array_keys($results[0]['lapSectors']) AS $sector) {
                usort($results, function ($a, $b) use ($sector) {
                    if (!isset($a['lapSectors'][$sector])) {
                        return 1;
                    } elseif (!isset($b['lapSectors'][$sector])) {
                        return -1;
                    } else {
                        return $a['lapSectors'][$sector] - $b['lapSectors'][$sector];
                    }
                });

                $sectorPositions = \Positions::addToArray($results, function ($a, $b) use ($sector) {
                    return isset($a['lapSectors'][$sector])
                    && isset($b['lapSectors'][$sector])
                    && $a['lapSectors'][$sector] == $b['lapSectors'][$sector];
                });

                foreach ($sectorPositions AS $key => $sectorPosition) {
                    $results[$key]['sectorPosition'][$sector] = $sectorPosition['position'];
                }
            }
        }

        usort($results, function($a, $b) {
            return $a['position'] - $b['position'];
        });

        return $results;
    }

    public function hasRace(AcRace $race)
    {
        foreach($race->entrants AS $entrant) {
            if ($entrant->race_position) {
                return true;
            }
        }

        return false;
    }

    public function race(AcRace $race)
    {
        $results = [];
        $bestTime = $lastTime = $numLaps = 0;
        $bestLapEntrant = $race->entrants()->where('race_fastest_lap_position', 1)->first();
        $bestLap = $bestLapEntrant ? $bestLapEntrant->raceFastestLap->time : null;

        foreach($race->entrants()->with('raceFastestLap', 'qualifyingLap', 'championshipEntrant.driver.nation')->orderBy('race_position')->get() AS $entrant) {
            if ($entrant->race_position) {

                $gapToBestLap = null;
                if ($entrant->raceFastestLap && $bestLap) {
                    $gapToBestLap = $entrant->raceFastestLap->time - $bestLap;
                }

                $gapToBest = null;
                $gapToLast = null;
                $lapsBehind = null;
                if ($entrant->race_time) {
                    if ($bestTime) {
                        $gapToBest = $entrant->race_time - $bestTime;
                        $lapsBehind = $numLaps - $entrant->race_laps;
                    } else {
                        $bestTime = $entrant->race_time;
                        $numLaps = $entrant->race_laps;
                    }
                    if ($lastTime) {
                        $gapToLast = $entrant->race_time - $lastTime;
                    }
                    $lastTime = $entrant->race_time;
                }

                $results[] = [
                    'position' => $entrant->race_position,
                    'driver' => $entrant->championshipEntrant->driver,
                    'colour' => $entrant->championshipEntrant->colour,
                    'colour2' => $entrant->championshipEntrant->colour2,
                    'number' => $entrant->championshipEntrant->number,
                    'rookie' => $entrant->championshipEntrant->rookie,
                    'car' => $entrant->car,
                    'laps' => $entrant->race_laps,
                    'lapsBehind' => $lapsBehind,
                    'time' => $entrant->race_time,
                    'DSQ' => $entrant->race_disqualified,
                    'DNF' => $entrant->race_retired,
                    'toBest' => $gapToBest,
                    'toLast' => $gapToLast,
                    'positionChange' => $entrant->qualifying_position - $entrant->race_position,
                    'fastestLapPosition' => $entrant->race_fastest_lap_position,
                    'lap' => $entrant->raceFastestLap,
                    'toBestLap' => $gapToBestLap,
                ];
            }
        }

        return $results;
    }

    public function summary(AcChampionship $championship)
    {
        $summary = [];
        foreach($championship->races AS $race) {
            foreach($this->qualifying($race) AS $qualifying) {
                if ($qualifying['position'] == 1) {
                    $summary[$race->id]['pole'] = $qualifying;
                }
            }
            foreach($this->race($race) AS $result) {
                if ($result['position'] == 1) {
                    $summary[$race->id]['winner'] = $result;
                }
                if ($result['fastestLapPosition'] == 1) {
                    $summary[$race->id]['fastestLap'] = $result;
                }
            }
        }

        return $summary;
    }

    public function lapChart(AcRace $race)
    {
        $entrantLapCount = [];
        $entrantPositions = [];
        $lapped = [0];

        // Build the lap chart
        $laps = [];
        foreach($race->entrants AS $entrant) {
            foreach($entrant->raceLaps AS $raceLap) {
                $laps[] = [
                    'time' => $raceLap->time,
                    'entrant' => $entrant,
                ];
            }
            $entrantLapCount[$entrant->championshipEntrant->driver->id] = 0;
            $entrantPositions[$entrant->championshipEntrant->driver->id] = [$entrant->qualifying_position];
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
        foreach($race->entrants AS $entrant) {
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


    public function forDriver(Driver $driver)
    {
        $results['all'] = $this->getAllForDriver($driver);
        $results['best'] = $this->getBestForDriver($results['all']);

        return $results;
    }

    protected function getAllForDriver(Driver $driver)
    {
        $driver->load('acEntries.entries.race.championship');

        $championships = [];

        foreach($driver->acEntries AS $entry) {
            foreach($entry->entries->sortBy(function($entry) {
                return $entry->race->time;
            }) AS $result) {

                $championshipID = $result->race->championship->id;
                $raceID = $result->race->id;

                if (!isset($championships[$championshipID])) {
                    // Load back down the chain
                    $result->race->championship->load('entrants.driver', 'races.entrants.championshipEntrant.driver');
                    $points = \ACDriverPoints::forChampionship(
                        AcPointsSystem::where('default', true)->first(),
                        $result->race->championship
                    );
                    $points = array_where($points, function($key, $value) use ($driver) {
                        return $value['entrant']->driver->id == $driver->id;
                    });
                    $driverPoints = array_pop($points);

                    $championships[$championshipID] = [
                        'championship' => $result->race->championship,
                        'position' => $result->race->championship->isComplete()
                            ? $driverPoints['position']
                            : NULL,
                        'racePositions' => $driverPoints['positions'],
                        'races' => [],
                    ];
                }

                if ($result->race->canBeReleased()) {
                    // Load back down the chain
                    $qualifying = $this->qualifying($result->race);
                    $qualifying = array_where($qualifying, function($key, $value) use ($driver) {
                        return $value['driver']->id == $driver->id;
                    });

                    $results = $this->race($result->race);
                    $results = array_where($results, function($key, $value) use ($driver) {
                        return $value['driver']->id == $driver->id;
                    });

                    $championships[$championshipID]['races'][$raceID] = [
                        'race' => $result->race,
                        'result' => $result,
                        'qualifying' => array_pop($qualifying),
                        'results' => array_pop($results),
                    ];
                }
            }
        }

        return $championships;
    }


    protected function getBestForDriver($results)
    {
        $bests = [
            'championship' => [],
            'qualifying' => [],
            'race' => [],
            'raceLap' => [],
        ];

        foreach($results AS $champID => $championship) {
            foreach($championship['races'] AS $raceID => $race) {
                $this->getBest($bests['race'], $race, function($a) {
                    return $a['result']->race_position;
                });
                $this->getBest($bests['qualifying'], $race, function($a) {
                    return $a['result']->qualifying_position;
                });
                $this->getBest($bests['raceLap'], $race, function($a) {
                    return $a['result']->race_fastest_lap_position;
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
}
