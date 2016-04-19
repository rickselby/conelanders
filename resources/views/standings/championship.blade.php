@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('standings.index') }}">Standings</a></li>
        <li><a href="{{ route('standings.system', $system) }}">{{ $system->name }}</a></li>
        <li class="active">{{ $championship->name }}</li>
    </ol>
@endsection

@section('content')

    <p>
        <a href="{{ route('standings.overview', [$system, $championship]) }}"
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
                    <a href="{{ route('standings.season', [$system, $championship->id, $season->id]) }}" class="tablesorter-noSort">
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
                <th>{{ $detail['entity']->name }}</th>
                @foreach($seasons AS $season)
                    <td>{{ $detail['points'][$season->id] or '' }}</td>
                @endforeach
                <td>{{ $detail['total'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('tablesorter')

@endsection