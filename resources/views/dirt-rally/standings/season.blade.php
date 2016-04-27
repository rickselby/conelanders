@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('dirt-rally.standings.index') }}">Standings</a></li>
        <li><a href="{{ route('dirt-rally.standings.system', $system) }}">{{ $system->name }}</a></li>
        <li><a href="{{ route('dirt-rally.standings.championship', [$system, $season->championship]) }}">{{ $season->championship->name }}</a></li>
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
                <th>{{ $position + 1 }}</th>
                <th>
                    <a href="{{ route('driver.show', $detail['entity']) }}">
                        {{ $detail['entity']->name }}
                    </a>
                </th>
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