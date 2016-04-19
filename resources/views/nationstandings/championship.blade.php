@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('nationstandings.index') }}">Nations Standings</a></li>
        <li><a href="{{ route('nationstandings.system', $system) }}">{{ $system->name }}</a></li>
        <li class="active">{{ $championship->name }}</li>
    </ol>
@endsection

@section('content')

    <p>
        <a href="{{ route('nationstandings.overview', [$system, $championship->id]) }}"
           class="btn btn-primary" role="button">
            View all points on one page
        </a>
    </p>

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Nation</th>
            @foreach($seasons AS $season)
                <th data-sortInitialOrder="desc">
                    <a href="{{ route('nationstandings.season', [$system, $championship->id, $season->id]) }}" class="tablesorter-noSort">
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
                    <img src="{{ route('nation.image', $detail['entity']->id) }}" alt="{{ $detail['entity']->name }}" />
                    {{ $detail['entity']->name }}
                </th>
                @foreach($seasons AS $season)
                    <td>{{ isset($detail['points'][$season->id]) ? round($detail['points'][$season->id], 2) : '' }}</td>
                @endforeach
                <td>{{ round($detail['total'], 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('tablesorter')

@endsection
