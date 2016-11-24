@if (\RXEvent::canBeShown($event))

    @if (!$event->canBeReleased() && !\RXEvent::canBeShown($event))
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Full results not released yet</h3>
            </div>
            <div class="panel-body">
                This is a summary of results released so far... they may/will change when all the results
                are released.
            </div>
        </div>
    @endif

    <h3>Heat Results</h3>

    <table class="table sortable table-condensed">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($event->heats AS $session)
                <th>{{ $session->name }}</th>
            @endforeach
            <th>Heat Points</th>
            <th>Championship Points</th>
        </tr>
        </thead>
        <tbody>
        @foreach(\Positions::addEquals(\RXDriverStandings::heatsSummary($event)) AS $detail)
            <tr>
                <th>{{ $detail['position'] }}</th>
                <th>
                    @include('rallycross.driver.name', ['driver' => $detail['entrant']->driver])
                </th>
                @foreach($event->heats AS $session)
                    @if (isset($detail['points'][$session->id]))
                        <td class="position {{ \Positions::colour($detail['positions'][$session->id], $detail['points'][$session->id]) }}"
                            data-points="{{ $detail['points'][$session->id] }}"
                            data-position="{{ $detail['positions'][$session->id] }}">
                            {{ $detail['positions'][$session->id] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endforeach
                <td class="points">{{ $detail['totalPoints'] }}</td>
                <td class="points">{{ $detail['champPoints'] }}</td>
            </tr>
        @endforeach
        </tbody>

    </table>

@else
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Heat results not yet available</h3>
        </div>
        <div class="panel-body">
            The heat results summary is not yet available.
        </div>
    </div>
@endif
