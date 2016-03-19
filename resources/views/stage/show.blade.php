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

    @if (Auth::user() && Auth::user()->admin)
        {!! Form::open(['route' => ['season.event.stage.destroy', $stage->event->season->id, $stage->event->id, $stage->id], 'method' => 'delete', 'class' => 'form-inline']) !!}
            <a class="btn btn-small btn-warning"
               href="{{ route('season.event.stage.edit',
                   ['seasonID' => $stage->event->season->id, 'eventID' => $stage->event->id, 'stageID' => $stage->id]) }}">Edit stage</a>
            {!! Form::submit('Delete Stage', array('class' => 'btn btn-danger')) !!}
        {!! Form::close() !!}
        <p>
        </p>
    @endif

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

@endsection