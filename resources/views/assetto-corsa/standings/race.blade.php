@extends('page')

@section('content')

    @if(!$race->canBeReleased() && !(Auth::user() && Auth::user()->admin))
        @include('assetto-corsa.race-not-complete')
    @else
        @if (Auth::user() && Auth::user()->admin && !$race->canBeReleased())
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">Admin Only View</h3>
                </div>
                <div class="panel-body">
                    Only admins can see this page, the results have not yet been released
                </div>
            </div>
        @endif

        <h2>Qualifying</h2>

        @if (count($qualifying))
            <table class="table sortable table-condensed">
                <thead>
                <tr>
                    <th>Pos</th>
                    <th>Driver</th>
                    <th>Car</th>
                    @foreach($qualifying[0]['lap']->sectors AS $sector)
                        <th>Sector {{ $sector->sector }}</th>
                    @endforeach
                    <th>Laptime</th>
                    <th>Gap to 1st</th>
                    <th>Gap ahead</th>
                </tr>
                </thead>
                <tbody>
                @foreach($qualifying AS $qual)
                    <tr>
                        <th>{{ $qual['position'] }}</th>
                        <th>
                            @include('assetto-corsa.driver.name', ['entrant' => $qual])
                        </th>
                        <td>{{ $qual['car'] }}</td>
                        @if ($qual['lap'])
                            @foreach($qual['lap']->sectors AS $sector)
                                <td class="time {{ \Positions::colour($qual['sectorPosition'][$sector->sector]) }}">
                                    {{ Times::toString($sector->time) }}
                                </td>
                            @endforeach
                        @else
                            @foreach($qualifying[0]['lap']->sectors AS $sector)
                                <td></td>
                            @endforeach
                        @endif
            			<td class="time"><strong>{{ $qual['lap'] ? Times::toString($qual['lap']->time) : '' }}</strong></td>
                        <td class="time">{{ ($qual['toBest'] > 0) ? '+'.Times::toString($qual['toBest']) : '-' }}</td>
                        <td class="time">{{ ($qual['toLast'] > 0) ? '+'.Times::toString($qual['toLast']) : '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @if (count($results))
            <h2>Race</h2>

            <table class="table sortable table-condensed">
                <thead>
                <tr>
                    <th>Pos</th>
                    <th>Driver</th>
                    <th>Car</th>
                    <th>Laps</th>
                    <th>Total Time</th>
                    <th>Gap to 1st</th>
                    <th>Gap ahead</th>
                    <th colspan="2">Change</th>
                    <th>Fastest Lap</th>
                    <th>Best Lap Delta</th>
                </tr>
                </thead>
                <tbody>
                @foreach($results AS $result)
                    <tr>
                        <th>{{ $result['position'] }}</th>
                        <th>
                            @include('assetto-corsa.driver.name', ['entrant' => $result])
                        </th>
                        <td>{{ $result['car'] }}</td>
                        <td class="text-center">{{ $result['laps'] }}</td>
                        <td class="time">{{ Times::toString($result['time']) }}</td>
                        <td class="time">{{ ($result['toBest'] > 0) ? '+'.Times::toString($result['toBest']) : '-' }}</td>
                        <td class="time">{{ ($result['toLast'] > 0) ? '+'.Times::toString($result['toLast']) : '-' }}</td>
                        <td class="text-right">{{ abs($result['positionChange']) }}</td>
                        <td>
                            @if ($result['positionChange'] > 0)
                                <span class="glyphicon glyphicon-chevron-up" style="color: lightgreen" aria-hidden="true"></span>
                            @elseif ($result['positionChange'] < 0)
                                <span class="glyphicon glyphicon-chevron-down" style="color: red" aria-hidden="true"></span>
                            @endif
                        </td>
                        <td class="time {{ \Positions::colour($result['fastestLapPosition']) }}">
                            {{ $result['lap'] ? Times::toString($result['lap']->time) : '-' }}
                        </td>
                        <td class="time">{{ ($result['toBestLap'] > 0) ? '+'.Times::toString($result['toBestLap']) : '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <h2>Lap Chart</h2>

            <img src="{{ route('assetto-corsa.standings.race.lapchart', [$system, $race->championship, $race]) }}" />
        @endif

    @endif

@endsection
