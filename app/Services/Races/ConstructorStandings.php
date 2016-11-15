<?php

namespace App\Services\Races;

use App\Interfaces\Races\DriverStandingsInterface;
use App\Models\Races\RacesCar;
use App\Models\Races\RacesChampionship;
use App\Models\Races\RacesEvent;
use App\Models\Races\RacesSession;
use App\Models\Races\RacesSessionEntrant;

class ConstructorStandings extends Standings implements DriverStandingsInterface
{
    /**
     * {@inheritdoc}
     */
    public function eventSummary(RacesEvent $event)
    {
        $results = [];

        switch ($event->championship->constructors_count) {
            case self::SUM:
                $results = $this->eventSessionSummary($event, [$this, 'sumPoints']);
                break;
            case self::AVERAGE_SESSION:
                $results = $this->eventSessionSummary($event, [$this, 'averagePoints']);
                break;
            case self::AVERAGE_EVENT:
                if (\RacesEvent::canBeShown($event)) {
                    $results = $this->eventAverage($event);
                }
                break;
        }

        return $this->sortAndAddPositions($results, $event);
    }

    /**
     * {@inheritdoc}
     */
    public function event(RacesEvent $event)
    {
        $results = [];

        if (\RacesEvent::canBeShown($event)) {
            switch ($event->championship->constructors_count) {
                case self::SUM:
                    $results = $this->eventSessionCount($event, [$this, 'sumPoints']);
                    break;
                case self::AVERAGE_SESSION:
                    $results = $this->eventSessionCount($event, [$this, 'averagePoints']);
                    break;
                case self::AVERAGE_EVENT:
                    $results = $this->eventAverage($event);
                    break;
            }
        }

        return $this->sortAndAddPositions($results, $event);
    }

    /**
     * Get a count of points from each session, parsed by $func
     * @param RacesEvent $event
     * @param callable $func
     * @return mixed
     */
    protected function eventSessionCount(RacesEvent $event, callable $func)
    {
        $results = [];
        foreach($event->sessions AS $session) {
            if (\RacesSession::hasPoints($session)) {
                foreach ($this->sessionCount($session, $func) AS $result) {
                    $carID = $result['car']->id;

                    if (!isset($results[$carID])) {
                        $results[$carID] = $this->initCar($result['car']);
                    }

                    $results[$carID]['points'][$session->id] = $result['totalPoints'];
                    $results[$carID]['positions'][$session->id] = $result['position'];
                }
            }
        }

        return $this->sumPoints($results);
    }

    /**
     * Get a summary of points from each session, parsed by $func
     * @param RacesEvent $event
     * @param callable $func
     * @return mixed
     */
    protected function eventSessionSummary(RacesEvent $event, callable $func)
    {
        $results = [];
        foreach($event->sessions AS $session) {
            if (\RacesSession::hasPoints($session)) {
                foreach ($this->sessionCount($session, $func) AS $result) {
                    $carID = $result['car']->id;

                    if (!isset($results[$carID])) {
                        $results[$carID] = $this->initCar($result['car']);
                    }

                    if (\RacesSession::canBeShown($session)) {
                        $results[$carID]['points'][$session->id] = $result['totalPoints'];
                        $results[$carID]['positions'][$session->id] = $result['position'];
                    }
                }
            }
        }

        return $this->sumPoints($results);
    }

    /**
     * Get a count of session points, and call $func on the results
     * @param RacesSession $session
     * @param $func
     * @return mixed
     */
    protected function sessionCount(RacesSession $session, callable $func)
    {
        $results = [];

        foreach($session->entrants AS $entrant) {
            $carID = $entrant->car->id;

            if (!isset($results[$carID])) {
                $results[$carID] = $this->initCar($entrant->car);
            }

            $results[$carID]['points'][] = $entrant->points + $entrant->fastest_lap_points;
            $results[$carID]['positions'][] = $entrant->position;
        }

        return $this->sortAndAddPositions(call_user_func($func, $results));
    }

    /**
     * Average the car points at the event level
     * @param RacesEvent $event
     * @return mixed
     */
    protected function eventAverage(RacesEvent $event)
    {
        $results = [];
        foreach($event->sessions AS $session) {
            if (\RacesSession::hasPoints($session)) {
                foreach ($session->entrants AS $entrant) {
                    $carID = $entrant->car->id;
                    $entrantID = $entrant->championshipEntrant->id;

                    if (!isset($results[$carID])) {
                        $results[$carID] = $this->initCar($entrant->car);
                    }

                    if (!isset($results[$carID]['points'][$entrantID])) {
                        $results[$carID]['points'][$entrantID] = 0;
                    }

                    $results[$carID]['points'][$entrantID] += $entrant->points + $entrant->fastest_lap_points;
                    $results[$carID]['positions'][$entrantID] = $entrant->position;
                }
            }
        }

        return $this->averagePoints($results);
    }

    /**
     * Initialise a results entry for a car
     * @param RacesCar $car
     * @return array
     */
    protected function initCar(RacesCar $car)
    {
        return [
            'car' => $car,
            'points' => [],
            'positions' => [],
            'totalPoints' => 0,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function championship(RacesChampionship $championship)
    {
        $results = [];

        foreach(\RacesChampionships::cars($championship) AS $car) {
            $results[$car->id] = [
                'car' => $car,
                'points' => [],
                'positions' => [],
                'positionsWithEquals' => [],
                'dropped' => [],
                'totalPoints' => 0,
            ];
        }
        foreach($championship->events AS $event) {
            $eventResults = \RacesConstructorStandings::event($event);
            $eventResultsWithEquals = \Positions::addEquals($eventResults);
            foreach ($eventResults AS $key => $result) {
                $carID = $result['car']->id;
                $results[$carID]['points'][$event->id] = $result['totalPoints'];
                $results[$carID]['positions'][$event->id] = $result['position'];
                $results[$carID]['positionsWithEquals'][$event->id] = $eventResultsWithEquals[$key]['position'];
            }
        }

        $results = $this->sumPoints($results);

        // When the championship is complete, hide any drivers that have no results
        $results = $this->removeEmpty($championship, $results);

        $results = $this->dropEvents($championship, $results);

        return $this->sortAndAddPositions($results);
    }


    /**
     * Extend the points sorting method. If two cars have equal points, split them by
     * the car name. The arePointsEqual function will still return true for the two
     * cars, so they will get the same position. But it looks prettier.
     *
     * @param mixed $a
     * @param mixed $b
     * @return int
     */
    public function pointsSort($a, $b)
    {
        $val = parent::pointsSort($a, $b);
        if ($val == 0)
        {
            return strcmp($a['car']->name, $b['car']->name);
        } else {
            return $val;
        }
    }

}
