@extends('page')

@section('header')
    <div class="page-header">
        <h1>
            <a href="{{ route('standings.show', $system->id) }}">{{ $system->name }} Standings</a>:
            {{ $season->name }}
        </h1>
    </div>
@endsection

@section('content')

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($season->events AS $event)
                <th>
                    <a href="{{ route('standings.event', [$system->id, $season->id, $event->id]) }}">
                        {{ $event->name }}
                    </a>
                </th>
            @endforeach
            <th>Total Points</th>
        </tr>
        </thead>
        <tbody>
        @foreach($points AS $position => $detail)
            <tr>
                <th>{{ $position + 1 }}</th>
                <th>{{ $detail['driver']->name }}</th>
                @foreach($season->events AS $event)
                    <td>{{ $detail['events'][$event->id] or '' }}</td>
                @endforeach
                <td>{{ $detail['total'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection