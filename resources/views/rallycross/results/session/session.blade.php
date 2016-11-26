@if ($session->show && \RXEvent::canBeShown($event))

    @if (Gate::check('rallycross-admin') && !$session->canBeReleased())
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">Admin Only View</h3>
            </div>
            <div class="panel-body">
                Only admins and entrants can see this page, these results have not yet been released.
            </div>
        </div>
    @endif

    <h3>Results</h3>

    <div class="table-responsive">
        <table class="table sortable table-condensed">
            <thead>
            <tr>
                <th>Pos</th>
                <th>Driver</th>
                @if (\RXChampionships::multipleCars($session->event->championship))
                    <th data-sorter="false">Car</th>
                @endif
                @if (\RXSession::hasRaces($session))
                    <th>Race</th>
                @endif
                <th data-sorter="false">Time</th>
                @if (\RXSession::hasPenalties($session))
                    <th data-sorter="false">Penalty</th>
                @endif
                <th data-sorter="false">Gap to 1st</th>
                <th data-sorter="false" class="hidden-sm">Gap ahead</th>
                <th>Fastest Lap</th>
                <th data-sorter="false">
                    {{ $session->heat ? 'Heat ' : '' }}
                    Points
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach(\RXResults::forRace($session) AS $entrant)
                <tr>
                    @if ($entrant->dsq)
                        <th class="position-dsq">DSQ</th>
                    @elseif ($entrant->dnf)
                        <th class="position-dnf">Ret</th>
                    @else
                        <th>{{ $entrant->position }}</th>
                    @endif
                    <th>
                        @include('rallycross.driver.name', ['driver' => $entrant->eventEntrant->driver])
                    </th>
                    @if (\RXChampionships::multipleCars($session->event->championship))
                        <td>{{ $entrant->eventEntrant->car->short_name ?: '??' }}</td>
                    @endif
                    @if (\RXSession::hasRaces($session))
                        <td>{{ $entrant->race }}</th>
                    @endif
                    <td class="time">{{ Times::toString($entrant->time) }}</td>
                    @if (\RXSession::hasPenalties($session))
                        <td class="time">{{ Times::toString($entrant->penalty) }}</td>
                    @endif
                    <td class="time">
                        @if ($entrant->dsq || $entrant->dnf)
                            -
                        @elseif ($entrant->timeBehindFirst > 0)
                            {{ '+'.Times::toString($entrant->timeBehindFirst) }}
                        @endif
                    </td>
                    <td class="time hidden-sm">{{ ($entrant->timeBehindAhead > 0) ? '+'.Times::toString($entrant->timeBehindAhead) : '-' }}</td>
                    <td class="time">{{ Times::toString($entrant->lap) }}</td>
                    <td class="points">{{ $entrant->points }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@else
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">{{ $session->name }} results not yet available</h3>
        </div>
        <div class="panel-body">
            The results for this session are not yet available.
        </div>
    </div>
@endif
