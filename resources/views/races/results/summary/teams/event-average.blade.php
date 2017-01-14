
@if(\RacesEvent::canBeShown($event))

    @if ($event->championship->teams_group_by_size)
        @include('races.results.summary.teams.by-session-split')
    @else
        @include('races.results.summary.teams.by-session-table', ['table' => \RacesTeamStandings::eventSummary($event)])
    @endif

@else

    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">Full results not released yet</h3>
        </div>
        <div class="panel-body">
            Team results will be shown once the full event results are released.
        </div>
    </div>

@endif
