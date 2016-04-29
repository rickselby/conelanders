@extends('page')

@section('content')

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($seasons AS $season)
                <th>
                    <a href="{{ route('dirt-rally.times.season', [$championship, $season]) }}" class="tablesorter-noSort">
                        {{ $season->name }}
                    </a>
                </th>
            @endforeach
            <th>Total Time</th>
        </tr>
        </thead>
        <tbody>
        @foreach($times AS $position => $detail)
            <tr>
                <th>{{ $detail['position'] }}</th>
                <th>
                    <a href="{{ route('driver.show', $detail['driver']) }}">
                        {{ $detail['driver']->name }}
                    </a>
                </th>
                @foreach($seasons AS $season)
                    <td class="{{ $detail['dnss'][$season->id] ? 'danger' : '' }}">
                        @if ($season->isComplete())
                            {{ DirtRallyStageTime::toString($detail['seasons'][$season->id]) }}
                        @else
                            <em class="text-muted">
                                {{ DirtRallyStageTime::toString($detail['seasons'][$season->id]) }}
                            </em>
                        @endif
                    </td>
                @endforeach
                <td>{{ DirtRallyStageTime::toString($detail['total']) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('tablesorter')

    @include('dirt-rally.times.legend')

@endsection