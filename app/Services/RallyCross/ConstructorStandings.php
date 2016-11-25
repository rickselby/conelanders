<?php

namespace App\Services\RallyCross;

use App\Interfaces\RallyCross\ConstructorStandingsInterface;
use App\Models\RallyCross\RxCar;
use App\Models\RallyCross\RxChampionship;
use App\Models\RallyCross\RxEvent;
use App\Models\RallyCross\RxSession;

class ConstructorStandings extends Standings implements ConstructorStandingsInterface
{
    /**
     * {@inheritdoc}
     */
    public function eventSummary(RxEvent $event)
    {
        $results = [];

        switch ($event->championship->constructors_count) {
            case self::SUM:
                $results = $this->eventSessionSummary($event, [$this, 'sumPoints']);
                break;
            case self::AVERAGE:
                if (\RXEvent::canBeShown($event)) {
                    $results = $this->eventAverage($event);
                }
                break;
        }

        return $this->sortAndAddPositions($results, $event);
    }

    /**
     * {@inheritdoc}
     */
    public function event(RxEvent $event)
    {
        $results = [];

        if (\RXEvent::canBeShown($event)) {
            switch ($event->championship->constructors_count) {
                case self::SUM:
                    $results = $this->eventSessionCount($event, [$this, 'sumPoints']);
                    break;
                case self::AVERAGE:
                    $results = $this->eventAverage($event);
                    break;
            }
        }

        return $this->sortAndAddPositions($results, $event);
    }

    /**
     * Get a count of points from each session, parsed by $func
     * @param RxEvent $event
     * @param callable $func
     * @return mixed
     */
    protected function eventSessionCount(RxEvent $event, callable $func)
    {
        $results = [];

        if (\RXEvent::hasHeatPoints($event)) {
            foreach($event->heatResult AS $heatResult) {
                $carID = $heatResult->entrant->car->id;

                if (!isset($results[$carID])) {
                    $results[$carID] = $this->initCar($heatResult->entrant->car);
                }

                $results[$carID]['points']['heats'] = $heatResult->points;
                $results[$carID]['positions']['heats'] = $heatResult->position;
            }
        }

        foreach($event->notHeats AS $session) {
            if ($session->show && \RXSession::hasPoints($session)) {
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
     * @param RxEvent $event
     * @param callable $func
     * @return mixed
     */
    protected function eventSessionSummary(RxEvent $event, callable $func)
    {
        $results = [];

        if (\RXEvent::hasHeatPoints($event)) {
            foreach($event->heatResult AS $heatResult) {
                $carID = $heatResult->entrant->car->id;

                if (!isset($results[$carID])) {
                    $results[$carID] = $this->initCar($heatResult->entrant->car);
                }

                $results[$carID]['points']['heats'] = $heatResult->points;
                $results[$carID]['positions']['heats'] = $heatResult->position;
            }
        }

        foreach($event->notHeats AS $session) {
            if ($session->show && \RXSession::hasPoints($session)) {
                foreach ($this->sessionCount($session, $func) AS $result) {
                    $carID = $result['car']->id;

                    if (!isset($results[$carID])) {
                        $results[$carID] = $this->initCar($result['car']);
                    }

                    if (\RXSession::canBeShown($session)) {
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
     * @param RxSession $session
     * @param $func
     * @return mixed
     */
    protected function sessionCount(RxSession $session, callable $func)
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
     * @param RxEvent $event
     * @return mixed
     */
    protected function eventAverage(RxEvent $event)
    {
        $results = [];

        foreach(\RXDriverStandings::eventSummary($event) AS $result) {
            $carID = $result['entrant']->car->id;

            if (!isset($results[$carID])) {
                $results[$carID] = $this->initCar($result['entrant']->car);
            }

            if (!isset($results[$carID]['points'])) {
                $results[$carID]['points'] = [];
            }

            $results[$carID]['points'][] = $result['totalPoints'];
            $results[$carID]['positions'][] = $result['position'];
        }

        return $this->averagePoints($results);
    }

    /**
     * Initialise a results entry for a car
     * @param RxCar $car
     * @return array
     */
    protected function initCar(RxCar $car)
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
    public function championship(RxChampionship $championship)
    {
        $results = [];

        foreach(\RXChampionships::cars($championship) AS $car) {
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
            $eventResults = \RXConstructorStandings::event($event);
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
