@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('standings.index') }}">Standings</a></li>
        <li><a href="{{ route('standings.system', [$system->id]) }}">{{ $system->name }}</a></li>
        <li><a href="{{ route('standings.championship', [$system->id, $stage->event->season->championship->id]) }}">{{ $stage->event->season->championship->name }}</a></li>
        <li><a href="{{ route('standings.season', [$system->id, $stage->event->season->championship->id, $stage->event->season->id]) }}">{{ $stage->event->season->name }}</a></li>
        <li><a href="{{ route('standings.event', [$system->id, $stage->event->season->championship->id, $stage->event->season->id, $stage->event->id]) }}">{{ $stage->event->name }}</a></li>
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
                    <th>{{ $result->driver->name }}</th>
                    <td>{{ $result->dnf ? 'DNF' : StageTime::toString($result->time) }}</td>
                    <td>{{ $points['stage'][$result->position] or '' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @include('tablesorter')

    @endif

@endsection