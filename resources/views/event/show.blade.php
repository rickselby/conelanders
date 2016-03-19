@extends('page')

@section('header')
    <div class="page-header">
        <h1>
            <a href="{{ route('season.show', ['id' => $event->season->id]) }}">{{ $event->season->name }}</a>:
            {{ $event->name }}
        </h1>
    </div>
@endsection

@section('content')

    @if ($event->last_import)
    <p>Last update: {{ $event->last_import->toDayDateTimeString() }} UTC</p>
    @endif

    @if ($event->importing)
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Import in progress</h3>
            </div>
            <div class="panel-body">
                The results for this event are currently being imported; they will be available once the import is complete.
            </div>
        </div>
    @else

        @if (Auth::user() && Auth::user()->admin)
            {!! Form::open(['route' => ['season.event.destroy', $event->season->id, $event->id], 'method' => 'delete', 'class' => 'form-inline']) !!}
                <a class="btn btn-small btn-warning"
                   href="{{ route('season.event.edit', ['seasonID' => $event->season->id, 'eventID' => $event->id]) }}">Edit event</a>
                {!! Form::submit('Delete Event', array('class' => 'btn btn-danger')) !!}
            {!! Form::close() !!}
        @endif

        <h2>Standings</h2>
        @if (Auth::user() && Auth::user()->admin)
            <p>
                <a class="btn btn-small btn-info"
                   href="{{ route('season.event.stage.create', ['season_id' => $event->season->id, 'event_id' => $event->id]) }}">Add a stage</a>
            </p>
        @endif
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Driver</th>
                @foreach($event->stages AS $stage)
                <th>
                    <a href="{{ route('season.event.stage.show', ['season_id' => $event->season->id, 'event_id' => $event->id, 'stage_id' => $stage->id]) }}">
                        {{ $stage->name }}
                    </a>
                </th>
                @endforeach
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results AS $key => $result)
            <tr>
                <th>{{ $key+1 }}</th>
                <th>{{ $result['driver']->name }}</th>
                @foreach($event->stages AS $stage)
                <td>{{ StageTime::toString($result['stage'][$stage->order]) }}</td>
                @endforeach
                <td>{{ StageTime::toString($result['total']) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

    @endif {{-- importing test --}}

@endsection