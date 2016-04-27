@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('dirt-rally.championship.index') }}">Results</a></li>
        <li><a href="{{ route('dirt-rally.championship.show', $stage->event->season->championship) }}">{{ $stage->event->season->championship->name }}</a></li>
        <li><a href="{{ route('dirt-rally.championship.season.show', [$stage->event->season->championship, $stage->event->season]) }}">{{ $stage->event->season->name }}</a></li>
        <li><a href="{{ route('dirt-rally.championship.season.event.show', [$stage->event->season->championship, $stage->event->season, $stage->event]) }}">{{ $stage->event->name }}</a></li>
        <li class="active">{{ $stage->name }}</li>
    </ol>
@endsection

@section('content')

    @if ($stage->event->importing)
        @include('dirt-rally.import-in-progress')
    @else

        @if (Auth::user() && Auth::user()->admin)
            {!! Form::open(['route' => ['dirt-rally.championship.season.event.stage.destroy', $stage->event->season->championship, $stage->event->season, $stage->event, $stage], 'method' => 'delete', 'class' => 'form-inline']) !!}
                <a class="btn btn-small btn-warning"
                   href="{{ route('dirt-rally.championship.season.event.stage.edit', [$stage->event->season->championship, $stage->event->season, $stage->event, $stage]) }}">Edit stage</a>
                {!! Form::submit('Delete Stage', array('class' => 'btn btn-danger')) !!}
            {!! Form::close() !!}
            <p>
            </p>
        @endif

        <h2>Stage Results</h2>

        @if(!$stage->event->isComplete())
            @include('dirt-rally.event-not-complete-results')
        @endif

        @if ($stage->event->last_import && !$stage->event->isComplete())
            <p>Last update: {{ $stage->event->last_import->toDayDateTimeString() }} UTC</p>
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
                    <th>
                        <a href="{{ route('driver.show', $result->driver) }}">
                            {{ $result->driver->name }}
                        </a>
                    </th>
                    <td>{{ $result->dnf ? 'DNF' : DirtRallyStageTime::toString($result->time) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @include('tablesorter')

    @endif

@endsection