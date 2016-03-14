@extends('page')

@section('header')
    <div class="page-header">
        <h1>
            <a href="{{ route('season.show', ['season_id' => $stage->event->season->id]) }}">{{ $stage->event->season->name }}</a>:
            <a href="{{ route('season.event.show', ['season_id' => $stage->event->season->id, 'event_id' => $stage->event->id]) }}">{{ $stage->event->name }}</a>:
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
        @foreach($results AS $key => $result)
            <tr>
                <th>{{ $key+1 }}</th>
                <th>{{ $result->driver->name }}</th>
                <td>{{ StageTime::toString($result->time) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection