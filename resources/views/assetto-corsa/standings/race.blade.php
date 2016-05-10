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
                    <th data-sorter="false"></th>
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
                        <td>
                            @if ($qual['driver']->nation)
                                <img src="{{ route('nation.image', $qual['driver']->nation) }}" alt="{{ $qual['driver']->nation->name }}" />
                            @endif
                        </td>
                        <th>
                            @if ($qual['rookie'])
                                <span class="badge pull-right">R</span>
                            @endif
                            <span class="badge driver-number" style="background-color: {{ $qual['colour'] }}">
                                {{ $qual['number'] }}
                            </span>
                            <a href="{{ route('driver.show', $qual['driver']) }}">
                                {{ $qual['driver']->name }}
                            </a>
                        </th>
                        <td>{{ $qual['car'] }}</td>
                        @if ($qual['lap'])
                            @foreach($qual['lap']->sectors AS $sector)
                                <td class="{{ \Positions::colour($qual['sectorPosition'][$sector->sector]) }}">
                                    {{ Times::toString($sector->time) }}
                                </td>
                            @endforeach
                        @else
                            @foreach($qualifying[0]['lap']->sectors AS $sector)
                                <td></td>
                            @endforeach
                        @endif
                        <td>{{ $qual['lap'] ? Times::toString($qual['lap']->time) : '' }}</td>
                        <td>{{ ($qual['toBest'] > 0) ? '+'.Times::toString($qual['toBest']) : '-' }}</td>
                        <td>{{ ($qual['toLast'] > 0) ? '+'.Times::toString($qual['toLast']) : '-' }}</td>
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
                    <th data-sorter="false"></th>
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
                        <td>
                            @if ($result['driver']->nation)
                                <img src="{{ route('nation.image', $result['driver']->nation) }}" alt="{{ $result['driver']->nation->name }}" />
                            @endif
                        </td>
                        <th>
                            @if ($result['rookie'])
                                <span class="badge pull-right">R</span>
                            @endif
                            <span class="badge driver-number" style="background-color: {{ $result['colour'] }}">
                                {{ $result['number'] }}
                            </span>
                            <a href="{{ route('driver.show', $result['driver']) }}">
                                {{ $result['driver']->name }}
                            </a>
                        </th>
                        <td>{{ $result['car'] }}</td>
                        <td>{{ $result['laps'] }}</td>
                        <td>{{ Times::toString($result['time']) }}</td>
                        <td>{{ ($result['toBest'] > 0) ? '+'.Times::toString($result['toBest']) : '-' }}</td>
                        <td>{{ ($result['toLast'] > 0) ? '+'.Times::toString($result['toLast']) : '-' }}</td>
                        <td>{{ abs($result['positionChange']) }}</td>
                        <td>
                            @if ($result['positionChange'] > 0)
                                <span class="glyphicon glyphicon-chevron-up" style="color: lightgreen" aria-hidden="true"></span>
                            @elseif ($result['positionChange'] < 0)
                                <span class="glyphicon glyphicon-chevron-down" style="color: red" aria-hidden="true"></span>
                            @endif
                        </td>
                        <td class="{{ \Positions::colour($result['fastestLapPosition']) }}">
                            {{ $result['lap'] ? Times::toString($result['lap']->time) : '-' }}
                        </td>
                        <td>{{ ($result['toBestLap'] > 0) ? '+'.Times::toString($result['toBestLap']) : '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <h2>Lap Chart</h2>

            <img src="{{ route('assetto-corsa.standings.race.lapchart', [$system, $race->championship, $race]) }}" />
        @endif

    @endif {{-- importing test --}}


@endsection
