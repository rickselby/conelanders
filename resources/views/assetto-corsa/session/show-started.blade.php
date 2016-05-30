
<div class="panel {{ \ACSession::hasStartingPositions($session) ? 'panel-success' : 'panel-danger' }}">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" href="#started">
                Starting Positions
            </a> <span class="caret"></span>
        </h3>
    </div>
    <div class="panel-collapse collapse {{ \ACSession::hasStartingPositions($session) ? '' : 'in' }}" id="started" role="tabpanel">
        <div class="panel-body">
            @if (count($session->entrants))

                {!! Form::open(['route' => ['assetto-corsa.championship.event.session.entrants.started-session', $session->event->championship, $session->event, $session], 'class' => 'form-horizontal']) !!}
                <div class="form-group">
                    {!! Form::label('sequence', 'Set to results from:', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-9">
                        {!! Form::select('from-session', $sessions, null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        {!! Form::submit('Set Results', ['class' => 'btn btn-primary']) !!}
                    </div>
                </div>
                {!! Form::close() !!}

                {!! Form::open(['route' => ['assetto-corsa.championship.event.session.entrants.started', $session->event->championship, $session->event, $session], 'class' => 'form-horizontal']) !!}
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Pos.</th>
                        <th>Driver</th>
                        <th>Started</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($session->entrants()->orderBy('position')->get() AS $entrant)
                        <tr>
                            <th>{{ $entrant->position }}</th>
                            <th>{{ $entrant->championshipEntrant->driver->name }}</th>
                            <td>
                                {!! Form::text('started['.$entrant->id.']', $entrant->started, ['class' => 'form-control']) !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! Form::submit('Update Starting Positions', ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
            @else
                <p>No entrants yet - please upload results</p>
            @endif
        </div>
    </div>
</div>