<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" href="#time-penalties">
                Time Penalties
            </a> <span class="caret"></span>
        </h3>
    </div>
    <div class="panel-collapse collapse in" id="time-penalties" role="tabpanel">
        <div class="panel-body">
            @if (count($session->entrants))
                {!! Form::open(['route' => ['races.championship.event.session.entrants.time-penalties', $session->event->championship, $session->event, $session], 'class' => 'form-horizontal']) !!}
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Pos</th>
                        <th>Driver</th>
                        <th>Penalty Time</th>
                        <th>Penalty Reason</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($session->entrants()->orderBy('position')->get() AS $entrant)
                        <tr>
                            <th>{{ $entrant->position }}</th>
                            <th>{{ $entrant->championshipEntrant->driver->name }}</th>
                            <td class="col-sm-2">
                                {!! Form::text('penalty['.$entrant->id.']', \Times::toString($entrant->time_penalty), ['class' => 'form-control', 'placeholder' => 'm:ss']) !!}
                            </td>
                            <td>
                                {!! Form::text('penalty_reason['.$entrant->id.']', $entrant->time_penalty_reason, ['class' => 'form-control']) !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! Form::submit('Update Penalties', array('class' => 'btn btn-primary')) !!}
                {!! Form::close() !!}
            @else
                <p>No entrants yet - please upload results</p>
            @endif
        </div>
    </div>
</div>
