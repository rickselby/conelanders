@extends('page')

@section('content')

    @if ($stage->event->importing)
        @include('dirt-rally.import-in-progress')
    @elseif(!$stage->event->isComplete())
        @include('dirt-rally.event-not-complete')
    @else
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Driver</th>
                <th>Time</th>
                <th data-sortInitialOrder="desc">Points</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results AS $result)
                <tr>
                    <th>{{ $result['position'] }}</th>
                    <th>
                        <a href="{{ route('driver.show', $result['driver']) }}">
                            {{ $result['driver']['name'] }}
                        </a>
                    </th>
                    <td>{{ $result['dnf'] ? 'DNF' : DirtRallyStageTime::toString($result['time']) }}</td>
                    <td>{{ $points['stage'][intval($result['position'])] or '' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @include('tablesorter')

    @endif

@endsection