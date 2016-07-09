@extends('page')

@section('content')

    <p>
        <a href="{{ route('dirt-rally.standings.overview', $championship) }}"
           class="btn btn-primary" role="button">
            View all points on one page
        </a>
    </p>

    <table class="table sortable table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($championship->getOrderedSeasons() AS $season)
                <th data-sortInitialOrder="desc">
                    <a href="{{ route('dirt-rally.standings.season', [$championship, $season]) }}" class="tablesorter-noSort">
                        {{ $season->name }}
                    </a>
                </th>
            @endforeach
            <th data-sortInitialOrder="desc">Total Points</th>
        </tr>
        </thead>
        <tbody>
        @foreach($points AS $position => $detail)
            <tr>
                <th>{{ $detail['position'] }}</th>
                <th>
                    <a href="{{ route('driver.show', $detail['entity']) }}">
                        {{ $detail['entity']->name }}
                    </a>
                </th>
                @foreach($championship->getOrderedSeasons() AS $season)
                    <td class="points {{ \Positions::colour(isset($detail['positions'][$season->id]) ? $detail['positions'][$season->id] : null) }}">
                        @if ($season->isComplete())
                            {{ $detail['points'][$season->id] or '' }}
                        @else
                            <em class="text-muted">
                                {{ $detail['points'][$season->id] or '' }}
                            </em>
                        @endif
                    </td>
                @endforeach
                <td class="points">{{ $detail['total'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
