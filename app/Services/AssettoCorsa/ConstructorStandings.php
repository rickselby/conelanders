<?php

namespace App\Services\AssettoCorsa;

use App\Interfaces\AssettoCorsa\DriverStandingsInterface;
use App\Models\AssettoCorsa\AcCar;
use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcEvent;
use App\Models\AssettoCorsa\AcSession;

class ConstructorStandings extends Standings implements DriverStandingsInterface
{
    /**
     * {@inheritdoc}
     */
    public function eventSummary(AcEvent $event)
    {
        return $this->event($event);
    }

    /**
     * {@inheritdoc}
     */
    public function event(AcEvent $event)
    {
        $results = [];

        if (\ACEvent::canBeShown($event)) {
            switch ($event->championship->constructors_count) {
                case self::SUM:
                    $results = $this->eventSum($event);
                    break;
                case self::AVERAGE_SESSION:
                    $results = $this->eventSessionAverage($event);
                    break;
                case self::AVERAGE_EVENT:
                    $results = $this->eventAverage($event);
                    break;
            }
        }

        usort($results, [$this, 'pointsSort']);

        return \Positions::addToArray($results, [$this, 'arePointsEqual']);
    }

    /**
     * Sum the car points for the given event
     *
     * @param AcEvent $event
     *
     * @return mixed
     */
    protected function eventSum(AcEvent $event)
    {
        $results = [];
        foreach($event->sessions AS $session) {
            if (\ACSession::hasPoints($session)) {
                foreach ($session->entrants AS $entrant) {
                    $carID = $entrant->car->id;

                    if (!isset($results[$carID])) {
                        $results[$carID] = $this->initCar($entrant->car);
                        $results[$carID]['entrantList'] = [];
                    }
                    $results[$carID]['pointsList'][] = $entrant->points + $entrant->fastest_lap_points;
                    $results[$carID]['entrantList'][] = $entrant->championshipEntrant->id;

                    if ($session->type == AcSession::TYPE_RACE) {
                        $results[$carID]['positions'][] = $entrant->position;
                    }
                }
            }
        }

        foreach($results AS $id => $result) {
            $results[$id]['entrantList'] = array_unique($results[$id]['entrantList']);
        }

        return $this->sumAndSort($results);
    }

    /**
     * Average the car points at session level
     * @param AcEvent $event
     * @return mixed
     */
    protected function eventSessionAverage(AcEvent $event)
    {
        $results = [];
        foreach($event->sessions AS $session) {
            if (\ACSession::hasPoints($session)) {
                foreach ($this->sessionAverage($session) AS $result) {
                    $carID = $result['car']->id;

                    if (!isset($results[$carID])) {
                        $results[$carID] = $this->initCar($result['car']);
                    }

                    $results[$carID]['pointsList'][$session->id] = $result['points'];

                    if ($session->type == AcSession::TYPE_RACE) {
                        $results[$carID]['positions'][$session->id] = $result['position'];
                    }
                }
            }
        }

        return $this->sumAndSort($results);
    }

    /**
     * Get the average car points for the given session
     * @param AcSession $session
     * @return array|mixed
     */
    protected function sessionAverage(AcSession $session)
    {
        $results = [];

        foreach($session->entrants AS $entrant) {
            $carID = $entrant->car->id;

            if (!isset($results[$carID])) {
                $results[$carID] = $this->initCar($entrant->car);
            }

            $results[$carID]['pointsList'][] = $entrant->points + $entrant->fastest_lap_points;

            if ($session->type == AcSession::TYPE_RACE) {
                $results[$carID]['positions'][] = $entrant->position;
            }
        }

        $results = $this->averageAndSort($results);

        usort($results, [$this, 'pointsSort']);

        if ($session->type == AcSession::TYPE_RACE) {
            return \Positions::addToArray($results, [$this, 'arePointsEqual']);
        } else {
            return $results;
        }
    }

    /**
     * Average the car points at the event level
     * @param AcEvent $event
     * @return mixed
     */
    protected function eventAverage(AcEvent $event)
    {
        $results = [];
        foreach($event->sessions AS $session) {
            if (\ACSession::hasPoints($session)) {
                foreach ($session->entrants AS $entrant) {
                    $carID = $entrant->car->id;
                    $entrantID = $entrant->championshipEntrant->id;

                    if (!isset($results[$carID])) {
                        $results[$carID] = $this->initCar($entrant->car);
                    }

                    if (!isset($results[$carID]['pointsList'][$entrantID])) {
                        $results[$carID]['pointsList'][$entrantID] = 0;
                    }

                    $results[$carID]['pointsList'][$entrantID] += $entrant->points + $entrant->fastest_lap_points;
                    if ($session->type == AcSession::TYPE_RACE) {
                        $results[$carID]['positions'][] = $entrant->position;
                    }
                }
            }
        }

        return $this->averageAndSort($results);
    }

    /**
     * Calculate the average of the pointsList and sort parts of the results
     * @param $results
     * @return mixed
     */
    protected function averageAndSort($results)
    {
        foreach($results AS $id => $result) {
            if (count($result['pointsList'])) {
                $results[$id]['points'] = array_sum($result['pointsList']) / count($result['pointsList']);
            } else {
                $results[$id]['points'] = 0;
            }
            arsort($results[$id]['pointsList']);
            asort($results[$id]['positions']);
        }
        return $results;
    }

    /**
     * Sum the pointsList and sort parts of the results
     * @param $results
     * @return mixed
     */
    protected function sumAndSort($results)
    {
        foreach($results AS $id => $result) {
            $results[$id]['points'] = array_sum($result['pointsList']);
            arsort($results[$id]['pointsList']);
            asort($results[$id]['positions']);
        }
        return $results;
    }

    /**
     * Initialise a results entry for a car
     * @param AcCar $car
     * @return array
     */
    protected function initCar(AcCar $car)
    {
        return [
            'car' => $car,
            'points' => 0,
            'pointsList' => [],
            'positions' => [],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function championship(AcChampionship $championship)
    {
        $results = [];

        foreach(\ACChampionships::cars($championship) AS $car) {
            $results[$car->id] = [
                'car' => $car,
                'points' => 0,
                'pointsList' => [],
                'eventPoints' => [],
                'positions' => [],
                'eventPositions' => [],
                'dropped' => [],
            ];
        }
        foreach($championship->events AS $event) {
            $eventResults = \ACConstructorStandings::event($event);
            $eventResultsWithEquals = \Positions::addEquals($eventResults);
            foreach ($eventResults AS $key => $result) {
                $carID = $result['car']->id;
                $results[$carID]['points'] += $result['points'];
                $results[$carID]['pointsList'][] = $result['points'];
                $results[$carID]['eventPoints'][$event->id] = $result['points'];
                $results[$carID]['positions'][] = $result['position'];
                $results[$carID]['eventPositions'][$event->id] = $eventResultsWithEquals[$key]['position'];
                rsort($results[$carID]['pointsList']);
                sort($results[$carID]['positions']);
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

}
