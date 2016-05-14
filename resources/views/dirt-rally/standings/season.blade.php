@extends('page')

@section('content')

    <table class="table sortable table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($season->events AS $event)
                <th data-sortInitialOrder="desc">
                    <a href="{{ route('dirt-rally.standings.event', [$system, $season->championship, $season, $event]) }}" class="tablesorter-noSort">
                        {{ $event->name }}
                    </a>
                </th>
            @endforeach
            <th data-sortInitialOrder="desc">Total Points</th>
        </tr>
        </thead>
        <tbody>
        @foreach($points AS $position => $detail)
            <tr>
                <th>{{ $detail['position'] }}</th>
                <th>
                    <a href="{{ route('driver.show', $detail['entity']) }}">
                        {{ $detail['entity']->name }}
                    </a>
                </th>
                @foreach($season->events AS $event)
                    <td class="points {{ \Positions::colour(isset($detail['positions'][$event->id]) ? $detail['positions'][$event->id] : null) }}">
                        {{ $detail['points'][$event->id] or '' }}
                    </td>
                @endforeach
                <td class="points">{{ $detail['total'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
