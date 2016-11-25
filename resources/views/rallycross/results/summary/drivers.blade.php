
<table class="table sortable table-condensed">
    <thead>
    <tr>
        <th>Pos.</th>
        <th>Driver</th>
        @if (\RXChampionships::multipleCars($event->championship))
            <th>Car</th>
        @endif
        @if (\RXEvent::hasHeatPoints($event))
            <th>Heats</th>
        @endif
        @foreach($event->notHeats AS $session)
            @if (\RXSession::hasPoints($session))
                <th>{{ $session->name }}</th>
            @endif
        @endforeach
        <th>Total Points</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\Positions::addEquals(\RXDriverStandings::eventSummary($event)) AS $detail)
        <tr>
            <th>{{ $detail['position'] }}</th>
            <th>
                @include('rallycross.driver.name', ['driver' => $detail['entrant']->driver])
            </th>
            @if (\RXChampionships::multipleCars($event->championship))
                <td>
                    {{ $detail['entrant']->car->short_name }}
                </td>
            @endif

            @if (\RXEvent::hasHeatPoints($event))
                @if (isset($detail['points']['heats']))
                    <td class="position {{ \Positions::colour($detail['positions']['heats'], $detail['points']['heats']) }}"
                        data-points="{{ $detail['points']['heats'] }}"
                        data-position="{{ $detail['positions']['heats'] }}">
                        {{ $detail['positions']['heats'] }}
                    </td>
                @else
                    <td></td>
                @endif
            @endif

            @foreach($event->notHeats AS $session)
                @if (\RXSession::hasPoints($session))
                    @if (isset($detail['points'][$session->id]))
                        <td class="position {{ \Positions::colour($detail['positions'][$session->id], $detail['points'][$session->id]) }}"
                            data-points="{{ $detail['points'][$session->id] }}"
                            data-position="{{ $detail['positions'][$session->id] }}">
                            {{ $detail['positions'][$session->id] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endif
            @endforeach
            <td class="points">{{ $detail['totalPoints'] }}</td>
        </tr>
    @endforeach
    </tbody>

</table>