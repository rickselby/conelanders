@extends('page')

@section('content')

    <h2>Standings</h2>

    <table class="table sortable table-condensed">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($events AS $event)
                <th>
                    <a href="{{ route('assetto-corsa.standings.event', [$championship, $event]) }}" class="tablesorter-noSort">
                        {{ \Helpers::getInitials($event->name) }}
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
                @foreach($events AS $event)
                    @if (isset($detail['eventPoints'][$event->id]))
                        <td class="position {{  \Positions::colour($detail['eventPositions'][$event->id], $detail['eventPoints'][$event->id]) }}">
                            {{ $detail['eventPositions'][$event->id] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endforeach
                <td class="points">{{ $detail['points'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
