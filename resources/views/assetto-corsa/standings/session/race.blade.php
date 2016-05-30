<div class="panel panel-default">
    <div class="panel-heading" role="tab">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" href="#ac-session-{{ $session->id }}">
                {{ $session->name }}
            </a>
            <span class="caret"></span>
        </h4>
    </div>
    <div id="ac-session-{{ $session->id }}" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body">

            <h4>Results</h4>

            <table class="table sortable table-condensed">
                <thead>
                <tr>
                    <th>Pos</th>
                    <th>Driver</th>
                    <th>Car</th>
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
                        <td>{{ $entrant->car }}</td>
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

            <h4>Fastest Laps</h4>

            @include('assetto-corsa.standings.session.lap-table', ['lapTimes' => \ACResults::fastestLaps($session)])

            <h4>Lap Chart</h4>

            <img src="{{ route('assetto-corsa.standings.event.session.lapchart', [$session->event->championship, $session->event, $session]) }}"
                 style="width: 100%"
            />

        </div>
    </div>
</div>
