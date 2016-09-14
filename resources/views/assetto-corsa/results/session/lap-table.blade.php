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
            @if (isset($showLaps) && $showLaps)
                <th>Laps</th>
            @endif
            @if (count($lapTimes) && count($lapTimes[0]->fastestLap->sectors) > 1)
                @foreach($lapTimes[0]->fastestLap->sectors AS $sector)
                    <th>Sector {{ $sector->sector }}</th>
                @endforeach
            @endif
            <th data-sorter="false">Laptime</th>
            <th data-sorter="false">Gap to 1st</th>
            <th data-sorter="false">Gap ahead</th>
            @if ($session->type == \App\Models\AssettoCorsa\AcSession::TYPE_RACE ? \ACSession::hasFastestLapPoints($session) : \ACSession::hasPoints($session))
                <th data-sorter="false">Points</th>
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
                @if (count($session->event->championship->teams))
                    <td style="white-space: nowrap">
                        @if ($entrant->championshipEntrant->team)
                            {{ $entrant->championshipEntrant->team->short_name }}
                        @endif
                    </td>
                @endif
                @if (count(\ACChampionships::cars($session->event->championship)) > 1)
                    <td style="white-space: nowrap">{{ $entrant->car->short_name ?: '??' }}</td>
                @endif
                @if (\ACSession::hasBallast($session))
                    <td>{{ $entrant->ballast }}kg</td>
                @endif
                @if (isset($showLaps) && $showLaps)
                    <td>{{ count($entrant->laps) }}</td>
                @endif
                @if ($entrant->fastestLap && count($entrant->fastestLap->sectors) > 1)
                    @foreach($entrant->fastestLap->sectors AS $sector)
                        <td class="time {{ isset($entrant->sectorPosition[$sector->sector]) ? \Positions::colour($entrant->sectorPosition[$sector->sector]) : '' }}">
                            {{ Times::toString($sector->time) }}
                        </td>
                    @endforeach
                @elseif (count($lapTimes[0]->fastestLap->sectors) > 1)
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
</div>