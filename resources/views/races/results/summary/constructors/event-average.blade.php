@if(\RacesEvent::canBeShown($event))

    <table class="table sortable table-condensed">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Car</th>
            <th>Total Points</th>
            <th>Entrants</th>
            <th>Average Points</th>
        </tr>
        </thead>
        <tbody>
        @foreach(\Positions::addEquals(\RacesConstructorStandings::eventSummary($event)) AS $detail)
            <tr>
                <th>{{ $detail['position'] }}</th>
                <th>
                    {{ $detail['car']->name }}
                </th>
                <td class="points">
                    {{ array_sum($detail['points']) }}
                </td>
                <td class="points">
                    {{ count($detail['points']) }}
                </td>
                <td class="points">
                    {{ round($detail['totalPoints'], 2) }}
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>

@else

    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">Full results not released yet</h3>
        </div>
        <div class="panel-body">
            Constructors results will be shown once the full event results are released.
        </div>
    </div>

@endif
