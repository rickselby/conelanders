@extends('page')

@section('content')

    <p>
        <a href="{{ route('dirt-rally.standings.overview', [$system, $championship]) }}"
           class="btn btn-primary" role="button">
            View all points on one page
        </a>
    </p>

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($seasons AS $season)
                <th data-sortInitialOrder="desc">
                    <a href="{{ route('dirt-rally.standings.season', [$system, $championship, $season]) }}" class="tablesorter-noSort">
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
                @foreach($seasons AS $season)
                    <td>
                        @if ($season->isComplete())
                            {{ $detail['points'][$season->id] or '' }}
                        @else
                            <em class="text-muted">
                                {{ $detail['points'][$season->id] or '' }}
                            </em>
                        @endif
                    </td>
                @endforeach
                <td>{{ $detail['total'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('tablesorter')

@endsection