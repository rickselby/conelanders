@extends('page')

@section('content')

    <div class="panel panel-default">
        <ul class="list-group">
            @foreach($event->stages AS $stage)
                <li class="list-group-item">
                    <a href="{{ route('dirt-rally.times.stage', [$event->season->championship, $event->season, $event, $stage]) }}" class="tablesorter-noSort">
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

        <table class="table sortable table-bordered table-hover">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Driver</th>
                @foreach($event->stages AS $stage)
                    <th>
                        {{ $stage->ss }}
                    </th>
                @endforeach
                <th>Overall</th>
            </tr>
            </thead>
            <tbody>
            @foreach($times AS $detail)
            <tr>
                <th>{{ $detail['position'] }}</th>
                <th>
                    <a href="{{ route('driver.show', $detail['driver']) }}">
                        {{ $detail['driver']->name }}
                    </a>
                </th>
                @foreach($event->stages AS $stage)
                    <td class="time {{ isset($detail['worst'][$stage->order]) ? 'text-muted' : '' }} {{ \Positions::colour(isset($detail['stagePositions'][$stage->id]) ? $detail['stagePositions'][$stage->id] : null) }}">
                        {{ Times::toString($detail['stageTimes'][$stage->order]) }}
                    </td>
                @endforeach
                <td class="time">{{ Times::toString($detail['total']) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

        @include('dirt-rally.times.legend')

    @endif {{-- importing test --}}

@endsection
