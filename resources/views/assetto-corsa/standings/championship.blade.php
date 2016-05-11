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
                    @if ($detail['entrant']->driver->nation)
                        <img src="{{ route('nation.image', $detail['entrant']->driver->nation) }}" alt="{{ $detail['entrant']->driver->nation->name }}" />
                    @endif
                    @if ($detail['entrant']->rookie)
                        <span class="badge pull-right">R</span>
                    @endif
                    <span class="badge driver-number" style="background-color: {{ $detail['entrant']->colour }}">
                        {{ $detail['entrant']->number }}
                    </span>
                    <a href="{{ route('driver.show', $detail['entrant']->driver) }}">
                        {{ $detail['entrant']->driver->name }}
                    </a>
                </th>
                @foreach($races AS $race)
                    @if (isset($detail['races'][$race->id]))
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
                    @if ($summary[$race->id]['pole']['driver']->nation)
                        <img src="{{ route('nation.image', $summary[$race->id]['pole']['driver']->nation) }}"
                             alt="{{ $summary[$race->id]['pole']['driver']->nation->name }}" />
                    @endif
                    <span class="badge driver-number" style="background-color: {{ $summary[$race->id]['pole']['colour'] }}">
                        {{ $summary[$race->id]['pole']['number'] }}
                    </span>
                    {{ $summary[$race->id]['pole']['driver']->name }}
                </td>
                <td>
                    @if ($summary[$race->id]['fastestLap']['driver']->nation)
                        <img src="{{ route('nation.image', $summary[$race->id]['fastestLap']['driver']->nation) }}"
                             alt="{{ $summary[$race->id]['fastestLap']['driver']->nation->name }}" />
                    @endif
                    <span class="badge driver-number" style="background-color: {{ $summary[$race->id]['fastestLap']['colour'] }}">
                        {{ $summary[$race->id]['fastestLap']['number'] }}
                    </span>
                    {{ $summary[$race->id]['fastestLap']['driver']->name }}
                </td>
                <td>
                    @if ($summary[$race->id]['winner']['driver']->nation)
                        <img src="{{ route('nation.image', $summary[$race->id]['winner']['driver']->nation) }}"
                             alt="{{ $summary[$race->id]['winner']['driver']->nation->name }}" />
                    @endif
                    <span class="badge driver-number" style="background-color: {{ $summary[$race->id]['winner']['colour'] }}">
                        {{ $summary[$race->id]['winner']['number'] }}
                    </span>
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
