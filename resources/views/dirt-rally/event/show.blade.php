@extends('page')

@section('content')

    @if ($event->importing)
        @include('dirt-rally.import-in-progress')
    @else

        {!! Form::open(['route' => ['dirt-rally.championship.season.event.destroy', $event->season->championship, $event->season, $event], 'method' => 'delete', 'class' => 'form-inline']) !!}
            <a class="btn btn-small btn-warning"
               href="{{ route('dirt-rally.championship.season.event.edit', [$event->season->championship, $event->season, $event]) }}">Edit event</a>
            {!! Form::submit('Delete Event', array('class' => 'btn btn-danger')) !!}
        {!! Form::close() !!}

        <h2>Stages</h2>

        <p>
            <a class="btn btn-small btn-info"
               href="{{ route('dirt-rally.championship.season.event.stage.create', [$event->season->championship, $event->season, $event]) }}">Add a stage</a>
        </p>

        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
            </tr>
            </thead>
            <tbody>
            @forelse($event->stages AS $stage)
                <tr>
                    <td>{{ $stage->ss }}</td>
                    <td>
                        <a href="{{ route('dirt-rally.championship.season.event.stage.show', [$event->season->championship, $event->season, $event, $stage]) }}" class="tablesorter-noSort">
                            {{ $stage->stageInfo->stage_name }}
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">No stages</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <h2>Event Results</h2>

        @if(!$event->isComplete())
            @include('dirt-rally.event-not-complete-results')
        @endif

        @if ($event->last_import && !$event->isComplete())
            <p>Last update: {{ \Times::userTimezone($event->last_import) }}</p>
        @endif

        <table class="table sortable table-bordered table-hover">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Driver</th>
                @foreach($event->stages AS $stage)
                <th>{{ $stage->ss }}</th>
                @endforeach
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results AS $result)
            <tr>
                <th>{{ $result['position'] }}</th>
                <th>{{ $result['driver']->name }}</th>
                @foreach($event->stages AS $stage)
                <td class="time  {{ \Positions::colour(isset($result['stagePositions'][$stage->id]) ? $result['stagePositions'][$stage->id] : null) }}">
                    {{ Times::toString($result['stage'][$stage->order]) }}
                </td>
                @endforeach
                <td class="time">{{ Times::toString($result['total']) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

    @endif {{-- importing test --}}

@endsection
