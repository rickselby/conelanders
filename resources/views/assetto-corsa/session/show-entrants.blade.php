<div class="panel {{ count($session->entrants) ? 'panel-success' : 'panel-danger' }}">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" href="#entrants">
                Entrants
            </a> <span class="caret"></span>
        </h3>
    </div>
    <div class="panel-collapse collapse {{ count($session->entrants) ? '' : 'in' }}" id="entrants" role="tabpanel">
        <div class="panel-body">
            @if (count($session->entrants))
                {!! Form::open(['route' => ['assetto-corsa.championship.event.session.entrants.update', $session->event->championship, $session->event, $session], 'class' => 'form-horizontal']) !!}
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Pos</th>
                        <th>Driver</th>
                        <th>Car</th>
                        <th>Functions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($session->entrants()->orderBy('position')->get() AS $entrant)
                        <tr>
                            <th>{{ $entrant->position }}</th>
                            <th>{{ $entrant->championshipEntrant->driver->name }}</th>
                            <td>
                                {{ $entrant->car->name ?: '??' }}
                            </td>
                            <td>
                                @if ($entrant->canBeDeleted())
                                    <a class="btn btn-small btn-danger"
                                       href="{{ route('assetto-corsa.championship.event.session.entrants.destroy', [$session->event->championship, $session->event, $session, $entrant]) }}">
                                        Delete
                                    </a>
                                @endif
                                @if ($session->type == \App\Models\AssettoCorsa\AcSession::TYPE_RACE)
                                    DSQ {!! Form::checkbox('dsq['.$entrant->id.']', 1, $entrant->dsq) !!}
                                    DNF {!! Form::checkbox('dnf['.$entrant->id.']', 1, $entrant->dnf) !!}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! Form::submit('Update Entrants', array('class' => 'btn btn-primary')) !!}
                {!! Form::close() !!}
            @else
                <p>No entrants yet - please upload results</p>
            @endif
        </div>
    </div>
</div>