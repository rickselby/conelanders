
<h2>Drivers</h2>
@include('rallycross.results.summary.drivers')

@if (\RXChampionships::multipleCars($event->championship))
    <h2>Constructors</h2>
    @if ($event->championship->constructors_count == \App\Services\RallyCross\Standings::SUM)
        @include('rallycross.results.summary.constructors.by-session')
    @elseif ($event->championship->constructors_count == \App\Services\RallyCross\Standings::AVERAGE)
        @include('rallycross.results.summary.constructors.average')
    @endif
@endif
