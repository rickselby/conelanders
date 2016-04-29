@extends('page')

@section('content')

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Nation</th>
            @foreach($season->events AS $event)
                <th data-sortInitialOrder="desc">
                    <a href="{{ route('dirt-rally.nationstandings.event', [$system, $season->championship, $season, $event]) }}" class="tablesorter-noSort">
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
                    <img src="{{ route('nation.image', $detail['entity']) }}" alt="{{ $detail['entity']->name }}" />
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