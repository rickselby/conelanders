@extends('page')

@section('header')
    <div class="page-header">
        <h1>
            Results:
            <a href="{{ route('season.show', [$event->season->id]) }}">{{ $event->season->name }}</a>:
            {{ $event->name }}
        </h1>
    </div>
@endsection

@section('content')

    @if ($event->last_import)
    <p>Last update: {{ $event->last_import->toDayDateTimeString() }} UTC</p>
    @endif

    @if ($event->importing)
        @include('import-in-progress')
    @else

        @if (Auth::user() && Auth::user()->admin)
            {!! Form::open(['route' => ['season.event.destroy', $event->season->id, $event->id], 'method' => 'delete', 'class' => 'form-inline']) !!}
                <a class="btn btn-small btn-warning"
                   href="{{ route('season.event.edit', [$event->season->id, $event->id]) }}">Edit event</a>
                {!! Form::submit('Delete Event', array('class' => 'btn btn-danger')) !!}
            {!! Form::close() !!}
        @endif

        <h2>Standings</h2>

        @if (Auth::user() && Auth::user()->admin)
            <p>
                <a class="btn btn-small btn-info"
                   href="{{ route('season.event.stage.create', [$event->season->id, $event->id]) }}">Add a stage</a>
            </p>
        @endif

        @if(!$event->isComplete())
            @include('event-not-complete-results')
        @endif

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Driver</th>
                @foreach($event->stages AS $stage)
                <th>
                    <a href="{{ route('season.event.stage.show', [$event->season->id, $event->id, $stage->id]) }}">
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
                <th>{{ $key }}</th>
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