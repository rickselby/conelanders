@extends('page')
@push('stylesheets')
    <link href="{{ route('assetto-corsa.championship-css', $championship) }}" rel="stylesheet" />
@endpush


@section('content')

    <h2>Standings</h2>

    @if (\ACChampionships::shownBeforeRelease($championship))
        @include('unreleased')
    @endif

    <table class="table sortable table-condensed">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($events AS $event)
                <th>
                    <a href="{{ route('assetto-corsa.standings.event', [$championship, $event]) }}" class="tablesorter-noSort">
                        {{ $event->shortName }}
                    </a>
                </th>
            @endforeach
            <th data-sortInitialOrder="desc">Total Points</th>
        </tr>
        </thead>
        <tbody>
        @foreach($points AS $detail)
            <tr>
                <th>
                    {{ $detail['position'] }}
                </th>
                <th>
                    @include('assetto-corsa.driver.name', ['entrant' => $detail['entrant']])
                </th>
                @foreach($events AS $event)
                    @if (isset($detail['eventPoints'][$event->id]))
                        <td class="position {{ \Positions::colour($detail['eventPositions'][$event->id], $detail['eventPoints'][$event->id]) }} {{ in_array($event->id, $detail['dropped']) ? 'dropped' : '' }}">
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
