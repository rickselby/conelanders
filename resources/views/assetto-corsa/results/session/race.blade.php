<h3>Results</h3>

    <div class="table-responsive">
    <table class="table sortable table-condensed">
        <thead>
        <tr>
            <th>Pos</th>
            <th>Driver</th>
            @if (count($session->event->championship->teams))
                <th data-sorter="false">Team</th>
            @endif
            @if (count(\ACChampionships::cars($session->event->championship)) > 1)
                <th data-sorter="false">Car</th>
            @endif
            @if (\ACSession::hasBallast($session))
                <th data-sorter="false">Ballast</th>
            @endif
            <th data-sorter="false">Laps</th>
            <th data-sorter="false">Time</th>
            <th data-sorter="false">Gap to 1st</th>
            <th data-sorter="false" class="hidden-sm">Gap ahead</th>
            <th data-sorter="false">Change</th>
            <th data-sorter="false">Points</th>
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
                @if (count($session->event->championship->teams))
                    <td style="white-space: nowrap">
                        @if ($entrant->championshipEntrant->team)
                            {{ $entrant->championshipEntrant->team->short_name }}
                        @endif
                    </td>
                @endif
                @if (count(\ACChampionships::cars($session->event->championship)) > 1)
                    <td>{{ $entrant->car->short_name ?: '??' }}</td>
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
                <td class="time hidden-sm">{{ ($entrant->timeBehindAhead > 0) ? '+'.Times::toString($entrant->timeBehindAhead) : '-' }}</td>
                <td>
                    <div style="padding-left: 25%">
                    {{ abs($entrant->positionsGained) }}
                    @if ($entrant->positionsGained > 0)
                        <span class="glyphicon glyphicon-chevron-up" style="color: lightgreen" aria-hidden="true"></span>
                    @elseif ($entrant->positionsGained < 0)
                        <span class="glyphicon glyphicon-chevron-down" style="color: red" aria-hidden="true"></span>
                    @endif
                    </div>
                </td>
                <td class="points">{{ $entrant->points }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<h3>Fastest Laps</h3>

@include('assetto-corsa.results.session.lap-table', ['lapTimes' => \ACResults::fastestLaps($session)])

<h3>Lap Chart</h3>

<img src="{{ route('assetto-corsa.results.event.session.lapchart', [$session->event->championship, $session->event, $session]) }}"
     style="width: 100%"
/>
