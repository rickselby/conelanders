@extends('page')

@section('content')

    <table class="table sortable table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($championship->getOrderedSeasons() AS $season)
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
                @foreach($championship->getOrderedSeasons() AS $season)
                    <td class="time {{ $detail['dnss'][$season->id] ? 'danger' : '' }} {{ \Positions::colour(isset($detail['seasonPositions'][$season->id]) ? $detail['seasonPositions'][$season->id] : null) }}">
                        @if ($season->isComplete())
                            {{ Times::toString($detail['seasons'][$season->id]) }}
                        @else
                            <em class="text-muted">
                                {{ Times::toString($detail['seasons'][$season->id]) }}
                            </em>
                        @endif
                    </td>
                @endforeach
                <td class="time">{{ Times::toString($detail['total']) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('dirt-rally.times.legend')

@endsection
