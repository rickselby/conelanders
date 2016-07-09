@extends('page')

@section('content')

    <table class="table sortable table-bordered table-hover">
        <thead>
        <tr>
            <th colspan="2" data-sorter="false"></th>
            @foreach($championship->getOrderedSeasons() AS $season)
                <th colspan="{{ count($season->events) }}" data-sorter="false" class="text-center">
                    <a href="{{ route('dirt-rally.nationstandings.season', [$championship, $season]) }}" class="tablesorter-noSort">
                        {{ $season->name }}
                    </a>
                </th>
            @endforeach
            <th data-sorter="false"></th>
        </tr>
        <tr>
            <th>P.</th>
            <th>N.</th>
            @foreach($championship->getOrderedSeasons() AS $season)
                @foreach($season->events AS $event)
                    <th data-sortinitialorder="desc" class="text-center">
                        <a href="{{ route('dirt-rally.nationstandings.event', [$championship, $season, $event]) }}" class="tablesorter-noSort">
                            {{ substr($event->name, 0, 2) }}
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
                <th class="text-nowrap">
                    @include('nation.image', ['nation' => $detail['entity']])
                </th>
                @foreach($championship->getOrderedSeasons() AS $season)
                    @foreach($season->events AS $event)
                        <td>{{ isset($detail['points'][$event->id]) ? round($detail['points'][$event->id], 2) : '' }}</td>
                    @endforeach
                @endforeach
                <td>{{ round($detail['total'], 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
