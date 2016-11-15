<?php

namespace App\Services\Races;

use App\Interfaces\Races\DriverStandingsInterface;
use App\Models\Races\RacesCar;
use App\Models\Races\RacesChampionship;
use App\Models\Races\RacesEvent;
use App\Models\Races\RacesSession;
use App\Models\Races\RacesSessionEntrant;
use App\Models\Races\RacesTeam;

class TeamStandings extends Standings implements DriverStandingsInterface
{
    /**
     * {@inheritdoc}
     */
    public function eventSummary(RacesEvent $event)
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
                    $teamID = $result['team']->id;

                    if (!isset($results[$teamID])) {
                        $results[$teamID] = $this->initTeam($result['team']);
                    }

                    $results[$teamID]['points'][$session->id] = $result['totalPoints'];

                    if ($session->type == RacesSession::TYPE_RACE) {
                        $results[$teamID]['positions'][$session->id] = $result['position'];
                    }
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
                    $teamID = $result['team']->id;

                    if (!isset($results[$teamID])) {
                        $results[$teamID] = $this->initTeam($result['team']);
                    }

                    if (\RacesSession::canBeShown($session)) {
                        $results[$teamID]['points'][$session->id] = $result['totalPoints'];

                        if ($session->type == RacesSession::TYPE_RACE) {
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
     * @param RacesSession $session
     * @param $func
     * @return mixed
     */
    protected function sessionCount(RacesSession $session, callable $func)
    {
        $results = [];

        foreach($session->entrants AS $entrant) {
            if ($entrant->championshipEntrant->team) {
                $teamID = $entrant->championshipEntrant->team->id;

                if (!isset($results[$teamID])) {
                    $results[$teamID] = $this->initTeam($entrant->championshipEntrant->team);
                }

                $results[$teamID]['points'][] = $entrant->points + $entrant->fastest_lap_points;

                if ($session->type == RacesSession::TYPE_RACE) {
                    $results[$teamID]['positions'][] = $entrant->position;
                }
            }
        }

        return $this->sortAndAddPositions(call_user_func($func, $results));
    }

    /**
     * Average the team points at the event level
     * @param RacesEvent $event
     * @return mixed
     */
    protected function eventAverage(RacesEvent $event)
    {
        $results = [];
        foreach($event->sessions AS $session) {
            if (\RacesSession::hasPoints($session)) {
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

                        if ($session->type == RacesSession::TYPE_RACE) {
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
     * @param RacesTeam $team
     * @return array
     */
    protected function initTeam(RacesTeam $team)
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
    public function championship(RacesChampionship $championship)
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
            $eventResults = \RacesTeamStandings::event($event);
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

    /**
     * Extend the points sorting method. If two teams have equal points, split them by
     * the team name. The arePointsEqual function will still return true for the two
     * teams, so they will get the same position. But it looks prettier.
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
            return strcmp($a['team']->name, $b['team']->name);
        } else {
            return $val;
        }
    }
}
