@extends('page')

@section('content')

    <table class="table sortable table-bordered table-hover">
        <thead>
        <tr>
            <th colspan="2" rowspan="2" data-sorter="false"></th>
            @foreach($seasons AS $season)
                <th colspan="{{ $season->stageCount + count($season->events) }}" data-sorter="false" class="text-center">
                    <a href="{{ route('dirt-rally.standings.season', [$championship, $season]) }}" class="tablesorter-noSort">
                        {{ $season->name }}
                    </a>
                </th>
            @endforeach
            <th rowspan="2" data-sorter="false"></th>
        </tr>
        <tr>
            @foreach($seasons AS $season)
                @foreach($season->events AS $event)
                    <th colspan="{{ count($event->stages) + 1 }}" data-sorter="false" class="text-center">
                        <a href="{{ route('dirt-rally.standings.event', [$championship, $season, $event]) }}" class="tablesorter-noSort">
                            {{ $event->name }}
                        </a>
                    </th>
                @endforeach
            @endforeach
        </tr>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($seasons AS $season)
                @foreach($season->events AS $event)
                    @foreach($event->stages AS $stage)
                        <th data-sortInitialOrder="desc">
                            <a href="{{ route('dirt-rally.standings.stage', [$championship, $season, $event, $stage]) }}" class="tablesorter-noSort">
                                {{ $stage->order }}
                            </a>
                        </th>
                    @endforeach
                    <th data-sortInitialOrder="desc">
                        Ev
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
                    <a href="{{ route('driver.show', $detail['entity']) }}">
                        {{ $detail['entity']->name }}
                    </a>
                </th>
                @foreach($seasons AS $season)
                    @foreach($season->events AS $event)
                        @foreach($event->stages AS $stage)
                            <td>{{ $detail['stages'][$stage->id] or '' }}</td>
                        @endforeach
                        <td>{{ $detail['events'][$event->id] or '' }}</td>
                    @endforeach
                @endforeach
                <td>{{ $detail['total'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection