<?php

namespace App\Services\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcEvent;
use App\Models\AssettoCorsa\AcRace;

class DriverPoints
{
    
    public function forEvent(AcEvent $event) {
        $points = [];
    }
    
    public function forRace(AcRace $race)
    {
        $points = [];

        if ($race->canBeReleased()) {
            $system['qual'] = \PointSequences::get($race->championship->qualPointsSequence);
            $system['race'] = \PointSequences::get($race->championship->racePointsSequence);
            $system['laps'] = \PointSequences::get($race->championship->lapsPointsSequence);

            foreach($race->entrants AS $entrant) {

                $racePoints =
                    (isset($system['race'][$entrant->race_position])
                        && !$entrant->race_disqualified
                        && !$entrant->race_retired)
                    ? $system['race'][$entrant->race_position]
                    : 0;

                $lapsPoints =
                    isset($system['laps'][$entrant->race_fastest_lap_position])
                    ? $system['laps'][$entrant->race_fastest_lap_position]
                    : 0;

                $points[$entrant->championshipEntrant->driver->id] = [
                    'driver' => $entrant->championshipEntrant->driver,
                    'rookie' => $entrant->championshipEntrant->rookie,
                    'qualPosition' => $entrant->qualifying_position,
                    'racePoints' => $racePoints,
                    'racePosition' => $entrant->race_position,
                    'raceDSQ' => $entrant->race_disqualified,
                    'raceDNF' => $entrant->race_retired,
                    'lapsPoints' => $lapsPoints,
                    'lapsPosition' => $entrant->race_fastest_lap_position,
                    'points' => $racePoints + $lapsPoints,
                ];
            }

            // Sort by points and position
            usort($points, function ($a, $b) {
                if ($a['points'] != $b['points']) {
                    return $b['points'] - $a['points'];
                } else {
                    return $a['racePosition'] - $b['racePosition'];
                }
            });

            $points = \Positions::addToArray($points, [$this, 'areRacePointsEqual']);
        }

        return $points;
    }

    /**
     * Check two event results to see if they are equal
     * @param $a
     * @param $b
     * @return bool
     */
    public function areRacePointsEqual($a, $b)
    {
        return ($a['points'] == $b['points'])
            && ($a['racePosition'] == $b['racePosition']);
    }

    /**
     * Get points for the given system for the given season
     * @param AcChampionship $championship
     * @return array
     */
    public function forChampionship(AcChampionship $championship)
    {
        $championship->load('races.entrants.championshipEntrant.driver.nation');
        $points = [];
        foreach($championship->entrants AS $entrant) {
            $points[$entrant->driver->id]['entrant'] = $entrant;
            $points[$entrant->driver->id]['points'] = [];
            $points[$entrant->driver->id]['races'] = [];
            $points[$entrant->driver->id]['positions'] = [];
        }
        foreach($championship->races AS $race) {
            if ($race->canBeReleased()) {
                foreach ($this->forRace($race) AS $result) {
                    $points[$result['driver']->id]['points'][$race->id] = $result['points'];
                    $points[$result['driver']->id]['races'][$race->id] = $result;
                    $points[$result['driver']->id]['positions'][] = $result['position'];
                }
            }
        }

        return $this->sumAndSort($points);
    }

    /**
     * Take a list of points, sum them, and sort them...
     * @param array $points
     * @return array
     */
    protected function sumAndSort($points)
    {
        // Step through each driver, sum their points, and sort their positions
        foreach($points AS $driverID => $point) {
            $points[$driverID]['total'] = array_sum($point['points']);
            sort($points[$driverID]['positions']);
        }

        // Sort the drivers
        usort($points, [$this, 'pointsSort']);

        $points = \Positions::addToArray($points, [$this, 'arePointsEqual']);

        return $points;
    }

    /**
     * Sort overall points
     * @param mixed $a
     * @param mixed $b
     * @return int
     */
    protected function pointsSort($a, $b)
    {
        // First, total points
        if (!is_array($a['total'])) {
            if ($a['total'] != $b['total']) {
                return $b['total'] > $a['total'] ? 1 : -1;
            }
        } else {
            if ($a['total']['points'] != $b['total']['points']) {
                return $b['total']['points'] > $a['total']['points'] ? 1 : -1;
            }
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

        // There's nothing more we can do to separate these drivers!
        return 0;
    }

    /**
     * Check if points are equal
     * @param array $a
     * @param array $b
     * @return bool
     */
    public function arePointsEqual($a, $b)
    {
        return $a['total'] == $b['total']
            && $a['positions'] == $b['positions']
            && count($a['points']) == count($b['points']);
    }

}
