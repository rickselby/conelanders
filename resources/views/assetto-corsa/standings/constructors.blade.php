@extends('page')
@push('stylesheets')
    <link href="{{ route('assetto-corsa.championship-css', $championship) }}" rel="stylesheet" />
@endpush

@section('content')

    <h2>Constructors Standings</h2>

    @if (\ACChampionships::shownBeforeRelease($championship))
        @include('unreleased')
    @endif

    <table class="table sortable table-condensed">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Car</th>
            @foreach($events AS $event)
                <th>
                    <a href="{{ route('assetto-corsa.results.event', [$championship, $event]) }}" class="tablesorter-noSort">
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
                    {{ $detail['car']->full_name }}
                </th>
                @foreach($events AS $event)
                    @if (isset($detail['points'][$event->id]))
                        <td class="position {{ \Positions::colour($detail['positions'][$event->id], $detail['points'][$event->id]) }} {{ in_array($event->id, $detail['dropped']) ? 'dropped' : '' }}"
                            data-points="{{ round($detail['points'][$event->id], 2) }}"
                            data-position="{{ $detail['positionsWithEquals'][$event->id] }}">
                            {{ $detail['positionsWithEquals'][$event->id] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endforeach
                <td class="points">{{ round($detail['totalPoints'], 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
