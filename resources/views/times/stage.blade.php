@extends('page')

@section('header')
    <div class="page-header">
        <h1>
            <a href="{{ route('times.index') }}">Total Time</a>:
            <a href="{{ route('times.season', [$stage->event->season->id]) }}">{{ $stage->event->season->name }}</a>:
            <a href="{{ route('times.event', [$stage->event->season->id, $stage->event->id]) }}">{{ $stage->event->name }}</a>:
            {{ $stage->name }}
        </h1>
    </div>
@endsection

@section('content')

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

    @include('times.legend')

@endsection