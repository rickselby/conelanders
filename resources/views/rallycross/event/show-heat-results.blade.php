
<div class="panel {{ \RXEvent::areHeatsComplete($event) ? (\RXEvent::hasHeatResults($event) ? 'panel-success' : 'panel-danger') : 'panel-warning' }}">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" href="#heat-results">
                Heat Results
            </a> <span class="caret"></span>
        </h3>
    </div>
    <div class="panel-collapse collapse {{ \RXEvent::areHeatsComplete($event) && !\RXEvent::hasHeatResults($event) ? 'in' : '' }}" id="heat-results" role="tabpanel">
        <div class="panel-body">
            @if(\RXEvent::getHeatResults($event))

                @if(\RXEvent::areHeatsComplete($event))
                    {!! Form::open(['route' => ['rallycross.championship.event.heats-points-sequence', $event->championship, $event], 'class' => 'form-horizontal']) !!}
                    <div class="form-group">
                        {!! Form::label('sequence', 'Assign Points Sequence', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::select('sequence', $sequences, null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            {!! Form::submit('Assign Points', ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}

                    {!! Form::open(['route' => ['rallycross.championship.event.heats-points', $event->championship, $event], 'class' => 'form-horizontal']) !!}
                @else
                    <p>
                        All heat results must be complete before entering championship points for heats.
                    </p>
                @endif

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Pos.</th>
                        <th>Driver</th>
                        <th>Heat Points</th>
                        @if(\RXEvent::areHeatsComplete($event))
                            <th>Championship Points</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\RXEvent::getHeatResults($event) AS $entrant)
                        <tr>
                            <th>{{ $entrant['position'] }}</th>
                            <th>{{ $entrant['entrant']->driver->name }}</th>
                            <td>{{ $entrant['heatPoints'] }}</td>
                            @if(\RXEvent::areHeatsComplete($event))
                                <td>
                                    {!! Form::text('points['.$entrant['entrant']->id.']', $entrant['points'] , ['class' => 'form-control']) !!}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(\RXEvent::areHeatsComplete($event))
                    {!! Form::submit('Update Points', ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                @endif
            @else
                <p>No results yet</p>
            @endif
        </div>
    </div>
</div>
