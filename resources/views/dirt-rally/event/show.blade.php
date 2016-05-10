@extends('page')

@section('content')

    @if ($event->importing)
        @include('dirt-rally.import-in-progress')
    @else

        @if (Auth::user() && Auth::user()->admin)
            {!! Form::open(['route' => ['dirt-rally.championship.season.event.destroy', $event->season->championship, $event->season, $event], 'method' => 'delete', 'class' => 'form-inline']) !!}
                <a class="btn btn-small btn-warning"
                   href="{{ route('dirt-rally.championship.season.event.edit', [$event->season->championship, $event->season, $event]) }}">Edit event</a>
                {!! Form::submit('Delete Event', array('class' => 'btn btn-danger')) !!}
            {!! Form::close() !!}
        @endif

        <h2>Event Results</h2>

        @if (Auth::user() && Auth::user()->admin)
            <p>
                <a class="btn btn-small btn-info"
                   href="{{ route('dirt-rally.championship.season.event.stage.create', [$event->season->championship, $event->season, $event]) }}">Add a stage</a>
            </p>
        @endif

        @if(!$event->isComplete())
            @include('dirt-rally.event-not-complete-results')
        @endif

        @if ($event->last_import && !$event->isComplete())
            <p>Last update: {{ $event->last_import->toDayDateTimeString() }} UTC</p>
        @endif

        <table class="table sortable table-bordered table-hover">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Driver</th>
                @foreach($event->stages AS $stage)
                <th>
                    <a href="{{ route('dirt-rally.championship.season.event.stage.show', [$event->season->championship, $event->season, $event, $stage]) }}" class="tablesorter-noSort">
                        {{ count($event->stages) > 4 ? $stage->order : $stage->name }}
                    </a>
                </th>
                @endforeach
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results AS $result)
            <tr>
                <th>{{ $result['position'] }}</th>
                <th>
                    <a href="{{ route('driver.show', $result['driver']) }}">
                        {{ $result['driver']->name }}
                    </a>
                </th>
                @foreach($event->stages AS $stage)
                <td>{{ Times::toString($result['stage'][$stage->order]) }}</td>
                @endforeach
                <td>{{ Times::toString($result['total']) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

    @endif {{-- importing test --}}

@endsection
