@if (!$event->canBeReleased() && !\RacesEvent::canBeShown($event))
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">Full results not released yet</h3>
        </div>
        <div class="panel-body">
            This is a summary of results released so far... they may/will change when all the results
            are released.
        </div>
    </div>
@endif

<h2>Drivers</h2>
@include('races.results.summary.drivers')

@if (count($event->championship->teams))
    <h2>Teams</h2>
    @if ($event->championship->teams_count == \App\Services\Races\Standings::SUM)
        @include('races.results.summary.teams.by-session')
    @elseif ($event->championship->teams_count == \App\Services\Races\Standings::AVERAGE_SESSION)
        @include('races.results.summary.teams.by-session')
    @elseif ($event->championship->teams_count == \App\Services\Races\Standings::AVERAGE_EVENT)
        @include('races.results.summary.teams.event-average')
    @endif
@endif

@if (\RacesChampionships::multipleCars($event->championship))
    <h2>Constructors</h2>
    @if ($event->championship->constructors_count == \App\Services\Races\Standings::SUM)
        @include('races.results.summary.constructors.by-session')
    @elseif ($event->championship->constructors_count == \App\Services\Races\Standings::AVERAGE_SESSION)
        @include('races.results.summary.constructors.by-session')
    @elseif ($event->championship->constructors_count == \App\Services\Races\Standings::AVERAGE_EVENT)
        @include('races.results.summary.constructors.event-average')
    @endif
@endif
