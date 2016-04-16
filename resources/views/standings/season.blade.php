@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('standings.index') }}">Standings</a></li>
        <li><a href="{{ route('standings.system', [$system->id]) }}">{{ $system->name }}</a></li>
        <li><a href="{{ route('standings.championship', [$system->id, $season->championship->id]) }}">{{ $season->championship->name }}</a></li>
        <li class="active">{{ $season->name }}</li>
    </ol>
@endsection

@section('content')

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($season->events AS $event)
                <th data-sortInitialOrder="desc">
                    <a href="{{ route('standings.event', [$system->id, $season->championship->id, $season->id, $event->id]) }}">
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
                <th>{{ $position + 1 }}</th>
                <th>{{ $detail['driver']->name }}</th>
                @foreach($season->events AS $event)
                    <td>{{ $detail['points'][$event->id] or '' }}</td>
                @endforeach
                <td>{{ $detail['total'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('tablesorter')

@endsection