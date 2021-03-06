@extends('page')

@section('content')

    <div class="panel panel-default">
        <ul class="list-group">
            @foreach($event->stages AS $stage)
                <li class="list-group-item">
                    <a href="{{ route('dirt-rally.standings.stage', [$event->season->championship, $event->season, $event, $stage]) }}" class="tablesorter-noSort">
                        <strong>{{ $stage->ss }}:</strong>
                        {{ $stage->stageInfo->fullName }} : {{ $stage->time_of_day }} / {{ $stage->weather }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    @if ($event->importing)
        @include('dirt-rally.import-in-progress')
    @elseif(!$event->isComplete())
        @include('dirt-rally.event-not-complete')
    @else

        @if ($event->playlist)
            <p class="btn-group">
                @include('playlist.button', ['playlist' => $event->playlist])
            </p>
        @endif

        <table class="table sortable table-bordered table-hover">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Driver</th>
                @foreach($event->stages AS $stage)
                    <th colspan="2">
                        {{ $stage->ss }}
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
                    @include('nation.image', ['nation' => $detail['entity']->nation])
                    <a href="{{ route('driver.show', $detail['entity']) }}">
                        {{ $detail['entity']->name }}
                    </a>
                </th>
                @foreach($event->stages AS $stage)
                    <td class="time text-muted {{ \Positions::colour(isset($detail['stagePositions'][$stage->id]) ? $detail['stagePositions'][$stage->id] : null) }}">
                        {{ Times::toString($detail['stageTimes'][$stage->id]) }}
                    </td>
                    <td class="points">{{ $detail['stagePoints'][$stage->id] or '' }}</td>
                @endforeach
                <td class="time text-muted">{{ Times::toString($detail['total']['time']) }}</td>
                <td class="points">{{ $detail['eventPoints'] }}</td>
                <td class="points">{{ $detail['total']['points'] }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

    @endif {{-- importing test --}}

@endsection
