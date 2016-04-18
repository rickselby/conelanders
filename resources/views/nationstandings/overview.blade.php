@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('nationstandings.index') }}">Standings</a></li>
        <li><a href="{{ route('nationstandings.system', [$system->id]) }}">{{ $system->name }}</a></li>
        <li><a href="{{ route('nationstandings.championship', [$system->id, $championship->id]) }}">{{ $championship->name }}</a></li>
        <li class="active">Overview</li>
    </ol>
@endsection

@section('content')

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th colspan="2" data-sorter="false"></th>
            @foreach($seasons AS $season)
                <th colspan="{{ count($season->events) }}" data-sorter="false" class="text-center">
                    <a href="{{ route('nationstandings.season', [$system->id, $championship->id, $season->id]) }}" class="tablesorter-noSort">
                        {{ $season->name }}
                    </a>
                </th>
            @endforeach
            <th data-sorter="false"></th>
        </tr>
        <tr>
            <th>Pos.</th>
            <th>Nation</th>
            @foreach($seasons AS $season)
                @foreach($season->events AS $event)
                    <th data-sorter="false" class="text-center">
                        <a href="{{ route('nationstandings.event', [$system->id, $championship->id, $season->id, $event->id]) }}" class="tablesorter-noSort">
                            {{ $event->name }}
                        </a>
                    </th>
                @endforeach
            @endforeach
            <th data-sortInitialOrder="desc">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($points AS $position => $detail)
            <tr>
                <th>{{ $detail['position'] }}</th>
                <th>
                    <img src="{{ route('nation.image', $detail['entity']->id) }}" />
                    {{ $detail['entity']->name }}
                </th>
                @foreach($seasons AS $season)
                    @foreach($season->events AS $event)
                        <td>{{ $detail['points'][$event->id] or '' }}</td>
                    @endforeach
                @endforeach
                <td>{{ $detail['total'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('tablesorter')

@endsection