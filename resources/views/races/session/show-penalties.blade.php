<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" href="#point-penalties">
                Point Penalties
            </a> <span class="caret"></span>
        </h3>
    </div>
    <div class="panel-collapse collapse" id="point-penalties" role="tabpanel">
        <div class="panel-body">
            @if (count($session->entrants))

                <h3>Add a Penalty</h3>
                {{ Form::open(['route' => ['races.championship.event.session.entrants.add-penalty', $session->event->championship, $session->event, $session], 'class' => 'form-horizontal']) }}
                <div class="form-group">
                    {!! Form::label('entrant', 'Driver', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-9">
                        {!! Form::select('entrant', $session->entrants->sortBy('championshipEntrant.driver.name', SORT_NATURAL|SORT_FLAG_CASE)->pluck('championshipEntrant.driver.name', 'id'), null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('points', 'Points', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-9">
                        {!! Form::number('points', null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('reason', 'Reason', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-9">
                        {!! Form::text('reason', null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        {!! Form::submit('Add Penalty', ['class' => 'btn btn-primary']) !!}
                    </div>
                </div>
                {!! Form::close() !!}

                <h3>Penalties</h3>

                <table class="table">
                    <thead>
                    <tr>
                        <th>Entrant</th>
                        <th>Points</th>
                        <th>Reason</th>
                        <th>Remove</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($penalties AS $penalty)
                        <tr>
                            <td>{{ $penalty->entrant->championshipEntrant->driver->name }}</td>
                            <td>{{ $penalty->points }}</td>
                            <td>{{ $penalty->reason }}</td>
                            <td>
                                {{ Form::open(['route' => ['races.championship.event.session.entrants.remove-penalty', $session->event->championship, $session->event, $session, $penalty], 'method' => 'delete']) }}
                                {{ Form::submit('Remove', ['class' => 'btn btn-danger btn-xs']) }}
                                {{ Form::close() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No penalties</td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>

            @else
                <p>No entrants yet - please upload results</p>
            @endif
        </div>
    </div>
</div>
