@extends('page')

@section('content')

    @include('dirt-rally.stage.summary')

    @if ($stage->event->importing)
        @include('dirt-rally.import-in-progress')
    @elseif(!$stage->event->isComplete())
        @include('dirt-rally.event-not-complete')
    @else
        <table class="table sortable table-bordered table-hover">
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
                        <span class="flag-icon flag-icon-{{ $result['driver']['nation']['acronym'] }}" style="font-size: 1.25em;"></span>
                        <a href="{{ route('driver.show', $result['driver']) }}">
                            {{ $result['driver']['name'] }}
                        </a>
                    </th>
                    <td class="time">{{ $result['dnf'] ? 'DNF' : Times::toString($result['time']) }}</td>
                    <td class="points">{{ $points[intval($result['position'])] or '' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @endif

@endsection
