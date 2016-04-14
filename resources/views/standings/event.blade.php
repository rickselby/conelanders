@extends('page')

@section('header')
    <div class="page-header">
        <h1>
            <a href="{{ route('standings.show', [$system->id]) }}">{{ $system->name }} Standings</a>:
            <a href="{{ route('standings.season', [$system->id, $event->season->id]) }}">{{ $event->season->name }}</a>:
            {{ $event->name }}
        </h1>
    </div>
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
                        <a href="{{ route('standings.stage', [$system->id, $event->season->id, $event->id, $stage->id]) }}">
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
                <th>{{ $detail['driver']->name }}</th>
                @foreach($event->stages AS $stage)
                    <td class="text-muted">{{ StageTime::toString($detail['stageTimes'][$stage->order]) }}</td>
                    <td>{{ $detail['stagePoints'][$stage->order] or '' }}</td>
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