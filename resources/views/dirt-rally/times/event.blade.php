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
                    <th>
                        <a href="{{ route('dirt-rally.times.stage', [$event->season->championship, $event->season, $event, $stage]) }}" class="tablesorter-noSort">
                            {{ $stage->name }}
                        </a>
                    </th>
                @endforeach
                <th>Overall</th>
            </tr>
            </thead>
            <tbody>
            @foreach($times['times'] AS $detail)
            <tr>
                <th>{{ $detail['position'] }}</th>
                <th>
                    <a href="{{ route('driver.show', $detail['driver']) }}">
                        {{ $detail['driver']->name }}
                    </a>
                </th>
                @foreach($event->stages AS $stage)
                    <td class="{{ isset($detail['worst'][$stage->order]) ? 'text-muted' : '' }}">
                        {{ DirtRallyStageTime::toString($detail['stageTimes'][$stage->order]) }}
                    </td>
                @endforeach
                <td>{{ DirtRallyStageTime::toString($detail['total']) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

        @include('tablesorter')

        @include('dirt-rally.times.legend')

    @endif {{-- importing test --}}

@endsection