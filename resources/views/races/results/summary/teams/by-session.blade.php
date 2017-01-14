
@if ($event->championship->teams_group_by_size)
    @include('races.results.summary.teams.by-session-split')
@else
    @include('races.results.summary.teams.by-session-table', ['table' => \RacesTeamStandings::eventSummary($event)])
@endif
