<table class="table sortable table-condensed">
    <thead>
    <tr>
        <th>Pos</th>
        <th>Driver</th>
        <th>Car</th>
        @if (\ACSession::hasBallast($session))
            <th>Ballast</th>
        @endif
        @if (isset($showLaps) && $showLaps)
            <th>Laps</th>
        @endif
        @foreach($lapTimes[0]->fastestLap->sectors AS $sector)
            <th>Sector {{ $sector->sector }}</th>
        @endforeach
        <th>Laptime</th>
        <th>Gap to 1st</th>
        <th>Gap ahead</th>
        @if ($session->type == \App\Models\AssettoCorsa\AcSession::TYPE_RACE ? \ACSession::hasFastestLapPoints($session) : \ACSession::hasPoints($session))
            <th>Points</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($lapTimes AS $entrant)
        <tr>
            <th>{{ $entrant->fastest_lap_position }}</th>
            <th>
                @include('assetto-corsa.driver.name', ['entrant' => $entrant->championshipEntrant])
            </th>
            <td>{{ $entrant->car }}</td>
            @if (\ACSession::hasBallast($session))
                <td>{{ $entrant->ballast }}kg</td>
            @endif
            @if (isset($showLaps) && $showLaps)
                <td>{{ count($entrant->laps) }}</td>
            @endif
            @if ($entrant->fastestLap)
                @foreach($entrant->fastestLap->sectors AS $sector)
                    <td class="time {{ isset($entrant->sectorPosition[$sector->sector]) ? \Positions::colour($entrant->sectorPosition[$sector->sector]) : '' }}">
                        {{ Times::toString($sector->time) }}
                    </td>
                @endforeach
            @else
                @foreach($lapTimes[0]->fastestLap->sectors AS $sector)
                    <td></td>
                @endforeach
            @endif
            <td class="time"><strong>{{ $entrant->fastestLap ? Times::toString($entrant->fastestLap->time) : '' }}</strong></td>
            <td class="time">{{ ($entrant->timeBehindFirst > 0) ? '+'.Times::toString($entrant->timeBehindFirst) : '-' }}</td>
            <td class="time">{{ ($entrant->timeBehindAhead > 0) ? '+'.Times::toString($entrant->timeBehindAhead) : '-' }}</td>
            @if ($session->type == \App\Models\AssettoCorsa\AcSession::TYPE_RACE ? \ACSession::hasFastestLapPoints($session) : \ACSession::hasPoints($session))
                <td class="points">{{ $session->type == \App\Models\AssettoCorsa\AcSession::TYPE_RACE ? $entrant->fastest_lap_points : $entrant->points }}</td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
