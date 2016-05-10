@extends('page')

@section('content')

    <h2>Standings</h2>

    <table class="table sortable table-condensed">
        <thead>
        <tr>
            <th>Pos.</th>
            <th data-sorter="false"></th>
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
                    @if ($detail['entrant']->driver->nation)
                        <img src="{{ route('nation.image', $detail['entrant']->driver->nation) }}" alt="{{ $detail['entrant']->driver->nation->name }}" />
                    @endif
                </th>
                <th>
                    <a href="{{ route('driver.show', $detail['entrant']->driver) }}">
                        {{ $detail['entrant']->driver->name }}
                    </a>
                    <span class="badge pull-left driver-number" style="background-color: {{ $detail['entrant']->colour }}">
                        {{ $detail['entrant']->number }}
                    </span>
                    @if ($detail['entrant']->rookie)
                        <span class="badge pull-right">R</span>
                    @endif
                </th>
                @foreach($races AS $race)
                    @if (isset($detail['races'][$race->id]))
                        <td class="{{ \Positions::colour($detail['races'][$race->id]['racePosition'], $detail['races'][$race->id]['racePoints']) }}">
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
                    @else
                        <td></td>
                    @endif
                @endforeach
                <td>{{ $detail['total'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h2>Summary</h2>

    <table class="table">
        <thead>
        <tr>
            <th>Race</th>
            <th colspan="2">Pole Position</th>
            <th colspan="2">Fastest Lap</th>
            <th colspan="2">Winning Driver</th>
        </tr>
        </thead>
        <tbody>
        @foreach($races AS $race)
        <tr>
            <th>{{ $race->name }}</th>
            @if ($race->canBeReleased())
                <td>
                    @if ($summary[$race->id]['pole']['driver']->nation)
                        <img src="{{ route('nation.image', $summary[$race->id]['pole']['driver']->nation) }}"
                             alt="{{ $summary[$race->id]['pole']['driver']->nation->name }}" />
                    @endif
                </td>
                <td>
                    {{ $summary[$race->id]['pole']['driver']->name }}
                    <span class="badge pull-left driver-number" style="background-color: {{ $summary[$race->id]['pole']['colour'] }}">
                        {{ $summary[$race->id]['pole']['number'] }}
                    </span>
                </td>
                <td>
                    @if ($summary[$race->id]['fastestLap']['driver']->nation)
                        <img src="{{ route('nation.image', $summary[$race->id]['fastestLap']['driver']->nation) }}"
                             alt="{{ $summary[$race->id]['fastestLap']['driver']->nation->name }}" />
                    @endif
                </td>
                <td>
                    {{ $summary[$race->id]['fastestLap']['driver']->name }}
                    <span class="badge pull-left driver-number" style="background-color: {{ $summary[$race->id]['fastestLap']['colour'] }}">
                        {{ $summary[$race->id]['fastestLap']['number'] }}
                    </span>
                </td>
                <td>
                    @if ($summary[$race->id]['winner']['driver']->nation)
                        <img src="{{ route('nation.image', $summary[$race->id]['winner']['driver']->nation) }}"
                             alt="{{ $summary[$race->id]['winner']['driver']->nation->name }}" />
                    @endif
                </td>
                <td>
                    {{ $summary[$race->id]['winner']['driver']->name }}
                    <span class="badge pull-left driver-number" style="background-color: {{ $summary[$race->id]['winner']['colour'] }}">
                        {{ $summary[$race->id]['winner']['number'] }}
                    </span>
                </td>
            @else
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            @endif
        </tr>
        @endforeach
        </tbody>
    </table>

@endsection