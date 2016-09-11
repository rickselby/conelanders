
<table class="table sortable table-condensed">
    <thead>
    <tr>
        <th>Pos.</th>
        <th>Car</th>
        <th>Entrants</th>
        <th>Total Points</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\Positions::addEquals(\ACConstructorStandings::eventSummary($event)) AS $detail)
        <tr>
            <th>{{ $detail['position'] }}</th>
            <th>
                {{ $detail['car']->full_name }}
            </th>
            <td class="points">
                {{ count($detail['entrantList']) }}
            </td>
            <td class="points">
                {{ round($detail['points'], 2) }}
            </td>
        </tr>
    @endforeach
    </tbody>

</table>
