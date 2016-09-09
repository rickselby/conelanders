<h3>Results</h3>

<table class="table sortable table-condensed">
    <thead>
    <tr>
        <th>Pos</th>
        <th>Driver</th>
        @if (count(\ACChampionships::cars($session->event->championship)) > 1)
            <th>Car</th>
        @endif
        @if (\ACSession::hasBallast($session))
            <th>Ballast</th>
        @endif
        <th>Laps</th>
        <th>Time</th>
        <th>Gap to 1st</th>
        <th>Gap ahead</th>
        <th colspan="2">Change</th>
        <th>Points</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\ACResults::forRace($session) AS $entrant)
        <tr>
            @if ($entrant->dsq)
                <th class="position-dsq">DSQ</th>
            @elseif ($entrant->dnf)
                <th class="position-dnf">Ret</th>
            @else
                <th>{{ $entrant->position }}</th>
            @endif
            <th>
                @include('assetto-corsa.driver.name', ['entrant' => $entrant->championshipEntrant])
            </th>
            @if (count(\ACChampionships::cars($session->event->championship)) > 1)
                <td>{{ $entrant->car->name ?: '??' }}</td>
            @endif
            @if (\ACSession::hasBallast($session))
                <td>{{ $entrant->ballast }}kg</td>
            @endif
            <td class="text-center">{{ count($entrant->laps) }}</td>
            <td class="time">{{ Times::toString($entrant->time) }}</td>
            <td class="time">
                @if ($entrant->dsq || $entrant->dnf)
                    -
                @elseif ($entrant->lapsBehindFirst)
                    {{ '+ '.$entrant->lapsBehindFirst.' lap'.($entrant->lapsBehindFirst > 1 ? 's' : '') }}
                @elseif ($entrant->timeBehindFirst > 0)
                    {{ '+'.Times::toString($entrant->timeBehindFirst) }}
                @endif
            </td>
            <td class="time">{{ ($entrant->timeBehindAhead > 0) ? '+'.Times::toString($entrant->timeBehindAhead) : '-' }}</td>
            <td class="text-right">{{ abs($entrant->positionsGained) }}</td>
            <td>
                @if ($entrant->positionsGained > 0)
                    <span class="glyphicon glyphicon-chevron-up" style="color: lightgreen" aria-hidden="true"></span>
                @elseif ($entrant->positionsGained < 0)
                    <span class="glyphicon glyphicon-chevron-down" style="color: red" aria-hidden="true"></span>
                @endif
            </td>
            <td class="points">{{ $entrant->points }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h3>Fastest Laps</h3>

@include('assetto-corsa.standings.session.lap-table', ['lapTimes' => \ACResults::fastestLaps($session)])

<h3>Lap Chart</h3>

<img src="{{ route('assetto-corsa.standings.event.session.lapchart', [$session->event->championship, $session->event, $session]) }}"
     style="width: 100%"
/>
