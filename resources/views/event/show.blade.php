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

    <h2>Standings</h2>
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


    @if (Auth::user() && Auth::user()->admin)
    <a class="btn btn-small btn-info"
       href="{{ route('season.event.stage.create', ['season_id' => $event->season->id, 'event_id' => $event->id]) }}">Add a stage</a>
    @endif

@endsection