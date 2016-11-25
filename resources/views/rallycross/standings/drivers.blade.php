@extends('page')

@section('content')

    <h2>Driver Standings</h2>

    @if (\RXChampionships::shownBeforeRelease($championship))
        @include('unreleased')
    @endif

    <table class="table sortable table-condensed">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($championship->events AS $event)
                <th>
                    <a href="{{ route('rallycross.results.event', [$championship, $event]) }}" class="tablesorter-noSort">
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
                    @include('rallycross.driver.name', ['driver' => $detail['entrant']])
                </th>
                @foreach($championship->events AS $event)
                    @if (isset($detail['points'][$event->id]))
                        <td class="position {{ \Positions::colour($detail['positions'][$event->id], $detail['points'][$event->id]) }} {{ in_array($event->id, $detail['dropped']) ? 'dropped' : '' }}"
                            data-points="{{ $detail['points'][$event->id] }}"
                            data-position="{{ $detail['positionsWithEquals'][$event->id] }}">
                            {{ $detail['positionsWithEquals'][$event->id] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endforeach
                <td class="points">
                    {{ $detail['totalPoints'] }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
