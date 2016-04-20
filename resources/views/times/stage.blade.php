@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('times.index') }}">Total Time</a></li>
        <li><a href="{{ route('times.championship', [$stage->event->season->championship]) }}">{{ $stage->event->season->championship->name }}</a></li>
        <li><a href="{{ route('times.season', [$stage->event->season->championship, $stage->event->season]) }}">{{ $stage->event->season->name }}</a></li>
        <li><a href="{{ route('times.event', [$stage->event->season->championship, $stage->event->season, $stage->event]) }}">{{ $stage->event->name }}</a></li>
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
            </tr>
            </thead>
            <tbody>
            @foreach($results AS $result)
                <tr>
                    <th>{{ $result->position }}</th>
                    <th>{{ $result->driver->name }}</th>
                    <td>{{ $result->dnf ? 'DNF' : StageTime::toString($result->time) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @include('tablesorter')

        @include('times.legend')

    @endif

@endsection