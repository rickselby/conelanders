@if (!$event->canBeReleased() && !\ACEvent::canBeShown($event))
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
@include('assetto-corsa.results.summary.drivers')


@if (count(\ACChampionships::cars($event->championship)) > 1)
    <h2>Constructors</h2>
    @if ($event->championship->constructors_count == \App\Services\AssettoCorsa\Standings::SUM)
        @include('assetto-corsa.results.summary.constructors.sum')
    @elseif ($event->championship->constructors_count == \App\Services\AssettoCorsa\Standings::AVERAGE_SESSION)
        @include('assetto-corsa.results.summary.constructors.session-average')
    @elseif ($event->championship->constructors_count == \App\Services\AssettoCorsa\Standings::AVERAGE_EVENT)
        @include('assetto-corsa.results.summary.constructors.event-average')
    @endif
@endif
