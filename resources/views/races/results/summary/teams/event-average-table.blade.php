<table class="table sortable table-condensed">
    <thead>
    <tr>
        <th>Pos.</th>
        <th>Team</th>
        <th>Total Points</th>
        <th>Entrants</th>
        <th>Average Points</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\Positions::addEquals($table) AS $detail)
        <tr>
            <th>{{ $detail['position'] }}</th>
            <th>
                @include('races.championship.team.badge', ['team' => $detail['team']])
                {{ $detail['team']->name }}
            </th>
            <td class="points">
                {{ array_sum($detail['points']) }}
            </td>
            <td class="points">
                {{ count($detail['points']) }}
            </td>
            <td class="points">
                {{ round($detail['totalPoints'], 2) }}
                @if($detail['penalties'])
                    <span class="penalties" title="{{ implode("\n", array_map(function($a) { return $a->championshipSummary; }, $detail['penalties'])) }}">&dagger;</span>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>

</table>
