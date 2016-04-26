@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('standings.index') }}">Standings</a></li>
        <li><a href="{{ route('standings.system', $system) }}">{{ $system->name }}</a></li>
        <li><a href="{{ route('standings.championship', [$system, $stage->event->season->championship]) }}">{{ $stage->event->season->championship->name }}</a></li>
        <li><a href="{{ route('standings.season', [$system, $stage->event->season->championship, $stage->event->season]) }}">{{ $stage->event->season->name }}</a></li>
        <li><a href="{{ route('standings.event', [$system, $stage->event->season->championship, $stage->event->season, $stage->event]) }}">{{ $stage->event->name }}</a></li>
        <li class="active">{{ $stage->name }}</li>
    </ol>
@endsection

@section('content')

    @if ($stage->event->importing)
        @include('import-in-progress')
    @elseif(!$stage->event->isComplete())
        @include('event-not-complete')
    @else
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Driver</th>
                <th>Time</th>
                <th data-sortInitialOrder="desc">Points</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results AS $result)
                <tr>
                    <th>{{ $result->position }}</th>
                    <th>
                        <a href="{{ route('driver.show', $result->driver) }}">
                            {{ $result->driver->name }}
                        </a>
                    </th>
                    <td>{{ $result->dnf ? 'DNF' : StageTime::toString($result->time) }}</td>
                    <td>{{ $points['stage'][$result->position] or '' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @include('tablesorter')

    @endif

@endsection