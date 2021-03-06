@extends('page')

@section('content')

    @if ($stage->event->importing)
        @include('dirt-rally.import-in-progress')
    @else

        {!! Form::open(['route' => ['dirt-rally.championship.season.event.stage.destroy', $stage->event->season->championship, $stage->event->season, $stage->event, $stage], 'method' => 'delete', 'class' => 'form-inline']) !!}
            <a class="btn btn-small btn-warning"
               href="{{ route('dirt-rally.championship.season.event.stage.edit', [$stage->event->season->championship, $stage->event->season, $stage->event, $stage]) }}">Edit stage</a>
            {!! Form::submit('Delete Stage', array('class' => 'btn btn-danger')) !!}
        {!! Form::close() !!}

        <h2>Stage Results</h2>

        @if(!$stage->event->isComplete())
            @include('dirt-rally.event-not-complete-results')
        @endif

        @if ($stage->event->last_import && !$stage->event->isComplete())
            <p>Last update: {{ \Times::userTimezone($stage->event->last_import) }}</p>
        @endif

        <table class="table sortable table-bordered table-hover">
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
                    <th>{{ $result['position'] }}</th>
                    <th>{{ $result['driver']['name'] }}</th>
                    <td class="time">{{ $result['dnf'] ? 'DNF' : Times::toString($result['time']) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @endif

@endsection
