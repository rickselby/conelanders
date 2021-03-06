@extends('page')
@push('stylesheets')
    <link href="{{ route('races.championship.entrant.css', $championship) }}" rel="stylesheet" />
@endpush


@section('content')

    <h2>Driver Standings</h2>

    @if (\RacesChampionships::shownBeforeRelease($championship))
        @include('unreleased')
    @endif

    <table class="table sortable table-condensed">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($events AS $event)
                <th>
                    <a href="{{ route('races.results.event', [$championship->category, $championship, $event]) }}" class="tablesorter-noSort">
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
                    @include('races.driver.name', ['entrant' => $detail['entrant']])
                </th>
                @foreach($events AS $event)
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
                    @if($detail['penalties'])
                        <span class="penalties" title="{{ implode("\n", array_map(function($a) { return $a->championshipSummary; }, $detail['penalties'])) }}">&dagger;</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
