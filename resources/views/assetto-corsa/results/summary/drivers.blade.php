
<table class="table sortable table-condensed">
    <thead>
    <tr>
        <th>Pos.</th>
        <th>Driver</th>
        @foreach($event->sessions AS $session)
            @if (\ACSession::hasPoints($session))
                <th>{{ $session->name }}</th>
            @endif
        @endforeach
        <th>Total Points</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\Positions::addEquals(\ACDriverStandings::eventSummary($event)) AS $detail)
        <tr>
            <th>{{ $detail['position'] }}</th>
            <th>
                @include('assetto-corsa.driver.name', ['entrant' => $detail['entrant']])
            </th>
            @foreach($event->sessions AS $session)
                @if (\ACSession::hasPoints($session))
                    @if (isset($detail['sessionPoints'][$session->id]))
                        <td class="position {{ \Positions::colour($detail['sessionPositions'][$session->id], $detail['sessionPoints'][$session->id]) }}"
                            data-points="{{ $detail['sessionPoints'][$session->id] }}"
                            data-position="{{ $detail['sessionPositions'][$session->id] }}">
                            {{ $detail['sessionPositions'][$session->id] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endif
            @endforeach
            <td class="points">{{ $detail['points'] }}</td>
        </tr>
    @endforeach
    </tbody>

</table>