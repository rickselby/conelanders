@extends('page')

@section('content')

    <table class="table sortable table-bordered table-hover">
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
                    @include('nation.image', ['nation' => $detail['entity']])
                    {{ $detail['entity']->name }}
                </th>
                @foreach($season->events AS $event)
                    <td class="points">{{ isset($detail['points'][$event->id]) ? round($detail['points'][$event->id], 2) : '' }}</td>
                @endforeach
                <td class="points">{{ round($detail['total'], 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
