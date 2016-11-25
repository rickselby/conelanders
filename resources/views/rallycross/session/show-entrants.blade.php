
<div class="panel panel-default panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" href="#show-results">
                Current Results:
            </a> <span class="caret"></span>
        </h3>
    </div>
    <div class="panel-collapse collapse {{ $session->show ? '' : 'in' }}" id="show-results" role="tabpanel">
        <div class="panel-body">
            @if (count($session->entrants))
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Pos</th>
                        <th>Driver</th>
                        <th>Race</th>
                        <th>Time</th>
                        <th>Penalty</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($session->entrants AS $entrant)
                        <tr>
                            <th>{{ $entrant->position }}</th>
                            <td>{{ $entrant->eventEntrant->driver->name }}</td>
                            <td>{{ $entrant->race }}</td>
                            <td>{{ Times::toString($entrant->time) }}</td>
                            <td>{{ Times::toString($entrant->penalty) }}</td>
                            <td>
                                {!! Form::open(['route' => ['rallycross.championship.event.session.entrant.destroy', $session->event->championship, $session->event, $session, $entrant], 'method' => 'delete', 'class' => 'form-inline']) !!}
                                    {!! Form::submit('Delete', array('class' => 'btn btn-danger btn-xs')) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                No entrants yet - please enter results
            @endif
        </div>
    </div>
</div>
