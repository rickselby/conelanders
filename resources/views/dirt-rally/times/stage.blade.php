@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('dirt-rally.times.index') }}">Total Time</a></li>
        <li><a href="{{ route('dirt-rally.times.championship', [$stage->event->season->championship]) }}">{{ $stage->event->season->championship->name }}</a></li>
        <li><a href="{{ route('dirt-rally.times.season', [$stage->event->season->championship, $stage->event->season]) }}">{{ $stage->event->season->name }}</a></li>
        <li><a href="{{ route('dirt-rally.times.event', [$stage->event->season->championship, $stage->event->season, $stage->event]) }}">{{ $stage->event->name }}</a></li>
        <li class="active">{{ $stage->name }}</li>
    </ol>
@endsection

@section('content')

    @if ($stage->event->importing)
        @include('dirt-rally.import-in-progress')
    @elseif(!$stage->event->isComplete())
        @include('dirt-rally.event-not-complete')
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
                    <th>
                        <a href="{{ route('driver.show', $result->driver) }}">
                            {{ $result->driver->name }}
                        </a>
                    </th>
                    <td>{{ $result->dnf ? 'DNF' : StageTime::toString($result->time) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @include('tablesorter')

        @include('dirt-rally.times.legend')

    @endif

@endsection