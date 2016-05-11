@extends('page')

@section('content')

    <table class="table sortable table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($season->events AS $event)
                <th>
                    <a href="{{ route('dirt-rally.times.event', [$season->championship, $season, $event]) }}" class="tablesorter-noSort">
                        {{ $event->name }}
                    </a>
                </th>
            @endforeach
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($times AS $detail)
            <tr>
                <th>{{ $detail['position'] }}</th>
                <th>
                    <a href="{{ route('driver.show', $detail['driver']) }}">
                        {{ $detail['driver']->name }}
                    </a>
                </th>
                @foreach($season->events AS $event)
                    <td class="time {{ $detail['dnss'][$event->id] ? 'danger' : ($detail['dnfs'][$event->id] ? 'warning' : '') }}">
                        {{ Times::toString($detail['events'][$event->id]) }}
                    </td>
                @endforeach
                <td class="time">{{ Times::toString($detail['total']) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('dirt-rally.times.legend')

@endsection
