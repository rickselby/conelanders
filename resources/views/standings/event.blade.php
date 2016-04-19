@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('standings.index') }}">Standings</a></li>
        <li><a href="{{ route('standings.system', [$system->id]) }}">{{ $system->name }}</a></li>
        <li><a href="{{ route('standings.championship', [$system->id, $event->season->championship]) }}">{{ $event->season->championship->name }}</a></li>
        <li><a href="{{ route('standings.season', [$system->id, $event->season->championship->id, $event->season->id]) }}">{{ $event->season->name }}</a></li>
        <li class="active">{{ $event->name }}</li>
    </ol>
@endsection

@section('content')

    @if ($event->importing)
        @include('import-in-progress')
    @elseif(!$event->isComplete())
        @include('event-not-complete')
    @else

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Driver</th>
                @foreach($event->stages AS $stage)
                    <th colspan="2">
                        <a href="{{ route('standings.stage', [$system->id, $event->season->championship->id, $event->season->id, $event->id, $stage->id]) }}" class="tablesorter-noSort">
                            {{ $stage->name }}
                        </a>
                    </th>
                @endforeach
                <th colspan="2">Overall</th>
                <th data-sortInitialOrder="desc">Total Points</th>
            </tr>
            </thead>
            <tbody>
            @foreach($points AS $position => $detail)
            <tr>
                <th>{{ $position + 1 }}</th>
                <th>{{ $detail['entity']->name }}</th>
                @foreach($event->stages AS $stage)
                    <td class="text-muted">{{ StageTime::toString($detail['stageTimes'][$stage->id]) }}</td>
                    <td>{{ $detail['stagePoints'][$stage->id] or '' }}</td>
                @endforeach
                <td class="text-muted">{{ StageTime::toString($detail['total']['time']) }}</td>
                <td>{{ $detail['eventPoints'] }}</td>
                <td>{{ $detail['total']['points'] }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

        @include('tablesorter')

    @endif {{-- importing test --}}

@endsection