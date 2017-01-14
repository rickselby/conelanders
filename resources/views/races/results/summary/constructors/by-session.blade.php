
<table class="table sortable table-condensed">
    <thead>
    <tr>
        <th>Pos.</th>
        <th>Car</th>
        @foreach($event->sessions AS $session)
            @if (\RacesSession::hasPoints($session))
                <th>{{ $session->name }}</th>
            @endif
        @endforeach
        <th>Total Points</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\Positions::addEquals(\RacesConstructorStandings::eventSummary($event)) AS $detail)
        <tr>
            <th>{{ $detail['position'] }}</th>
            <th>
                {{ $detail['car']->name }}
            </th>
            @foreach($event->sessions AS $session)
                @if (\RacesSession::hasPoints($session))
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
            <td class="points">
                {{ round($detail['totalPoints'], 2) }}
                @if($detail['penalties'])
                    <span class="penalties" title="{{ implode("\n", array_map(function($a) { return $a->teamEventSummary; }, $detail['penalties'])) }}">&dagger;</span>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>

</table>
