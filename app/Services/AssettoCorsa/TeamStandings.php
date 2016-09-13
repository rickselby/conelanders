<?php

namespace App\Services\AssettoCorsa;

use App\Interfaces\AssettoCorsa\DriverStandingsInterface;
use App\Models\AssettoCorsa\AcCar;
use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcEvent;
use App\Models\AssettoCorsa\AcSession;
use App\Models\AssettoCorsa\AcSessionEntrant;
use App\Models\AssettoCorsa\AcTeam;

class TeamStandings extends Standings implements DriverStandingsInterface
{
    /**
     * {@inheritdoc}
     */
    public function eventSummary(AcEvent $event)
    {
        $results = [];

        switch ($event->championship->teams_count) {
            case self::SUM:
                $results = $this->eventSessionSummary($event, [$this, 'sumPoints']);
                break;
            case self::AVERAGE_SESSION:
                $results = $this->eventSessionSummary($event, [$this, 'averagePoints']);
                break;
            case self::AVERAGE_EVENT:
                if (\ACEvent::canBeShown($event)) {
                    $results = $this->eventAverage($event);
                }
                break;
        }

        return $this->sortAndAddPositions($results);
    }

    /**
     * {@inheritdoc}
     */
    public function event(AcEvent $event)
    {
        $results = [];

        if (\ACEvent::canBeShown($event)) {
            switch ($event->championship->teams_count) {
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

        return $this->sortAndAddPositions($results);
    }

    /**
     * Get a count of points from each session, parsed by $func
     * @param AcEvent $event
     * @param callable $func
     * @return mixed
     */
    protected function eventSessionCount(AcEvent $event, callable $func)
    {
        $results = [];
        foreach($event->sessions AS $session) {
            if (\ACSession::hasPoints($session)) {
                foreach ($this->sessionCount($session, $func) AS $result) {
                    $teamID = $result['team']->id;

                    if (!isset($results[$teamID])) {
                        $results[$teamID] = $this->initTeam($result['team']);
                    }

                    $results[$teamID]['points'][$session->id] = $result['totalPoints'];

                    if ($session->type == AcSession::TYPE_RACE) {
                        $results[$teamID]['positions'][$session->id] = $result['position'];
                    }
                }
            }
        }

        return $this->sumPoints($results);
    }

    /**
     * Get a summary of points from each session, parsed by $func
     * @param AcEvent $event
     * @param callable $func
     * @return mixed
     */
    protected function eventSessionSummary(AcEvent $event, callable $func)
    {
        $results = [];
        foreach($event->sessions AS $session) {
            if (\ACSession::hasPoints($session)) {
                foreach ($this->sessionCount($session, $func) AS $result) {
                    $teamID = $result['team']->id;

                    if (!isset($results[$teamID])) {
                        $results[$teamID] = $this->initTeam($result['team']);
                    }

                    if (\ACSession::canBeShown($session)) {
                        $results[$teamID]['points'][$session->id] = $result['totalPoints'];

                        if ($session->type == AcSession::TYPE_RACE) {
                            $results[$teamID]['positions'][$session->id] = $result['position'];
                        }
                    }
                }
            }
        }

        return $this->sumPoints($results);
    }

    /**
     * Get a count of session points, and call $func on the results
     * @param AcSession $session
     * @param $func
     * @return mixed
     */
    protected function sessionCount(AcSession $session, callable $func)
    {
        $results = [];

        foreach($session->entrants AS $entrant) {
            if ($entrant->championshipEntrant->team) {
                $teamID = $entrant->championshipEntrant->team->id;

                if (!isset($results[$teamID])) {
                    $results[$teamID] = $this->initTeam($entrant->championshipEntrant->team);
                }

                $results[$teamID]['points'][] = $entrant->points + $entrant->fastest_lap_points;

                if ($session->type == AcSession::TYPE_RACE) {
                    $results[$teamID]['positions'][] = $entrant->position;
                }
            }
        }

        return $this->sortAndAddPositions(call_user_func($func, $results));
    }

    /**
     * Average the team points at the event level
     * @param AcEvent $event
     * @return mixed
     */
    protected function eventAverage(AcEvent $event)
    {
        $results = [];
        foreach($event->sessions AS $session) {
            if (\ACSession::hasPoints($session)) {
                foreach ($session->entrants AS $entrant) {
                    if ($entrant->championshipEntrant->team) {
                        $teamID = $entrant->championshipEntrant->team->id;
                        $entrantID = $entrant->championshipEntrant->id;

                        if (!isset($results[$teamID])) {
                            $results[$teamID] = $this->initTeam($entrant->championshipEntrant->team);
                        }

                        if (!isset($results[$teamID]['points'][$entrantID])) {
                            $results[$teamID]['points'][$entrantID] = 0;
                        }

                        $results[$teamID]['points'][$entrantID] += $entrant->points + $entrant->fastest_lap_points;

                        if ($session->type == AcSession::TYPE_RACE) {
                            $results[$teamID]['positions'][] = $entrant->position;
                        }
                    }
                }
            }
        }

        return $this->averagePoints($results);
    }

    /**
     * Initialise a results entry for a team
     * @param AcTeam $team
     * @return array
     */
    protected function initTeam(AcTeam $team)
    {
        return [
            'team' => $team,
            'points' => [],
            'positions' => [],
            'totalPoints' => 0,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function championship(AcChampionship $championship)
    {
        $results = [];

        foreach($championship->teams AS $team) {
            $results[$team->id] = [
                'team' => $team,
                'points' => [],
                'positions' => [],
                'positionsWithEquals' => [],
                'dropped' => [],
                'totalPoints' => 0,
            ];
        }
        foreach($championship->events AS $event) {
            $eventResults = \ACTeamStandings::event($event);
            $eventResultsWithEquals = \Positions::addEquals($eventResults);
            foreach ($eventResults AS $key => $result) {
                $teamID = $result['team']->id;
                $results[$teamID]['points'][$event->id] = $result['totalPoints'];
                $results[$teamID]['positions'][$event->id] = $result['position'];
                $results[$teamID]['positionsWithEquals'][$event->id] = $eventResultsWithEquals[$key]['position'];
            }
        }

        $results = $this->sumPoints($results);

        // When the championship is complete, hide any drivers that have no results
        $results = $this->removeEmpty($championship, $results);

        $results = $this->dropEvents($championship, $results);

        return $this->sortAndAddPositions($results);
    }

}
