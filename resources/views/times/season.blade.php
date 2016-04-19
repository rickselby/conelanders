@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('times.index') }}">Total Time</a></li>
        <li><a href="{{ route('times.championship', [$season->championship]) }}">{{ $season->championship->name }}</a></li>
        <li class="active">{{ $season->name }}</li>
    </ol>
@endsection

@section('content')

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($season->events AS $event)
                <th>
                    <a href="{{ route('times.event', [$season->championship->id, $season->id, $event->id]) }}" class="tablesorter-noSort">
                        {{ $event->name }}
                    </a>
                </th>
            @endforeach
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($times['times'] AS $position => $detail)
            <tr>
                <th>{{ $position + 1 }}</th>
                <th>{{ $detail['driver']->name }}</th>
                @foreach($season->events AS $event)
                    <td class="{{ $detail['dnss'][$event->id] ? 'danger' : ($detail['dnfs'][$event->id] ? 'warning' : '') }}">
                        {{ StageTime::toString($detail['events'][$event->id]) }}
                    </td>
                @endforeach
                <td>{{ StageTime::toString($detail['total']) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('tablesorter')

    @include('times.legend')

@endsection