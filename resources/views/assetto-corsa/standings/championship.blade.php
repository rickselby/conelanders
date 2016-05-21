@extends('page')

@section('content')

    <h2>Standings</h2>

    <table class="table sortable table-condensed">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($races AS $race)
                <th>
                    <a href="{{ route('assetto-corsa.standings.race', [$system, $championship, $race]) }}" class="tablesorter-noSort">
                        {{ \Helpers::getInitials($race->name) }}
                    </a>
                </th>
            @endforeach
            <th data-sortInitialOrder="desc">Total Points</th>
        </tr>
        </thead>
        <tbody>
        @foreach($points AS $position => $detail)
            <tr>
                <th>
                    {{ $detail['position'] }}
                </th>
                <th>
                    @include('assetto-corsa.driver.name', ['entrant' => $detail['entrant']])
                </th>
                @foreach($races AS $race)
                    @if (isset($detail['races'][$race->id]))
                        @if ($detail['races'][$race->id]['raceDSQ'])
                            <td class="position position-dsq">DSQ</td>
                        @elseif ($detail['races'][$race->id]['raceDNF'])
                            <td class="position position-dnf">Ret</td>
                        @else
                            <td class="position {{ \Positions::colour($detail['races'][$race->id]['racePosition'], $detail['races'][$race->id]['racePoints']) }}">
                                @if ($detail['races'][$race->id]['qualPosition'] == 1)
                                    <em>
                                @endif
                                @if ($detail['races'][$race->id]['lapsPosition'] == 1)
                                    <strong>
                                @endif
                                    {{  $detail['races'][$race->id]['racePosition'] or '' }}
                                @if ($detail['races'][$race->id]['lapsPosition'] == 1)
                                    </strong>
                                @endif
                                @if ($detail['races'][$race->id]['qualPosition'] == 1)
                                    </em>
                                @endif
                            </td>
                        @endif
                    @else
                        <td></td>
                    @endif
                @endforeach
                <td class="points">{{ $detail['total'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h2>Summary</h2>

    <table class="table">
        <thead>
        <tr>
            <th>Race</th>
            <th>Pole Position</th>
            <th>Fastest Lap</th>
            <th>Winning Driver</th>
        </tr>
        </thead>
        <tbody>
        @foreach($races AS $race)
        <tr>
            <th>
                <a href="{{ route('assetto-corsa.standings.race', [$system, $championship, $race]) }}">
                    {{ $race->name }}
                </a>
            </th>
            @if ($race->canBeReleased())
                <td>
                    @include('nation.image', ['nation' => $summary[$race->id]['pole']['driver']->nation])
                    @include('assetto-corsa.driver.badge', ['driver' => $summary[$race->id]['pole']])
                    {{ $summary[$race->id]['pole']['driver']->name }}
                </td>
                <td>
                    @include('nation.image', ['nation' => $summary[$race->id]['fastestLap']['driver']->nation])
                    @include('assetto-corsa.driver.badge', ['driver' => $summary[$race->id]['fastestLap']])
                    {{ $summary[$race->id]['fastestLap']['driver']->name }}
                </td>
                <td>
                    @include('nation.image', ['nation' => $summary[$race->id]['winner']['driver']->nation])
                    @include('assetto-corsa.driver.badge', ['driver' => $summary[$race->id]['winner']])
                    {{ $summary[$race->id]['winner']['driver']->name }}
                </td>
            @else
                <td colspan="3" class="text-muted">
                    @if ($race->release)
                        {{ $race->release->format('l jS F Y, H:i e') }}
                    @endif
                </td>
            @endif
        </tr>
        @endforeach
        </tbody>
    </table>

@endsection
