@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('nationstandings.index') }}">Nations Standings</a></li>
        <li><a href="{{ route('nationstandings.system', [$system->id]) }}">{{ $system->name }}</a></li>
        <li><a href="{{ route('nationstandings.championship', [$system->id, $season->championship]) }}">{{ $season->championship->name }}</a></li>
        <li class="active">{{ $season->name }}</li>
    </ol>
@endsection

@section('content')

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Nation</th>
            @foreach($season->events AS $event)
                <th data-sortInitialOrder="desc">
                    <a href="{{ route('nationstandings.event', [$system->id, $season->championship->id, $season->id, $event->id]) }}" class="tablesorter-noSort">
                        {{ $event->name }}
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
                @foreach($season->events AS $event)
                    <td>{{ isset($detail['points'][$event->id]) ? round($detail['points'][$event->id], 2) : '' }}</td>
                @endforeach
                <td>{{ round($detail['total'], 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('tablesorter')

@endsection
