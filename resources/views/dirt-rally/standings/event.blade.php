@extends('page')

@section('content')

    @if ($event->importing)
        @include('dirt-rally.import-in-progress')
    @elseif(!$event->isComplete())
        @include('dirt-rally.event-not-complete')
    @else

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Driver</th>
                @foreach($event->stages AS $stage)
                    <th colspan="2">
                        <a href="{{ route('dirt-rally.standings.stage', [$system, $event->season->championship, $event->season, $event, $stage]) }}" class="tablesorter-noSort">
                            {{ count($event->stages) > 4 ? $stage->order : $stage->name }}
                        </a>
                    </th>
                @endforeach
                <th colspan="2">Overall</th>
                <th data-sortInitialOrder="desc">Total Points</th>
            </tr>
            </thead>
            <tbody>
            @foreach($points AS $detail)
            <tr>
                <th>{{ $detail['position'] }}</th>
                <th>
                    <a href="{{ route('driver.show', $detail['entity']) }}">
                        {{ $detail['entity']->name }}
                    </a>
                </th>
                @foreach($event->stages AS $stage)
                    <td class="text-muted">{{ DirtRallyStageTime::toString($detail['stageTimes'][$stage->id]) }}</td>
                    <td>{{ $detail['stagePoints'][$stage->id] or '' }}</td>
                @endforeach
                <td class="text-muted">{{ DirtRallyStageTime::toString($detail['total']['time']) }}</td>
                <td>{{ $detail['eventPoints'] }}</td>
                <td>{{ $detail['total']['points'] }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

        @include('tablesorter')

    @endif {{-- importing test --}}

@endsection
