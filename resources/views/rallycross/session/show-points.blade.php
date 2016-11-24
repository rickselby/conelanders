<div class="panel {{ \RXSession::hasPoints($session, true) ? 'panel-success' : 'panel-warning' }}">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" href="#points">
                Points
            </a> <span class="caret"></span>
        </h3>
    </div>
    <div class="panel-collapse collapse" id="points" role="tabpanel">
        <div class="panel-body">
            @if (count($session->entrants))
                {!! Form::open(['route' => ['rallycross.championship.event.session.entrants.points-sequence', $session->event->championship, $session->event, $session], 'class' => 'form-horizontal']) !!}
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

                {!! Form::open(['route' => ['rallycross.championship.event.session.entrants.points', $session->event->championship, $session->event, $session], 'class' => 'form-horizontal']) !!}
               <table class="table table-striped">
                   <thead>
                   <tr>
                       <th>Pos.</th>
                       <th>Driver</th>
                       <th>Points</th>
                   </tr>
                   </thead>
                   <tbody>
                   @foreach($session->entrants()->orderBy('position')->get() AS $entrant)
                       <tr>
                           <th>{{ $entrant->position }}</th>
                           <th>{{ $entrant->eventEntrant->driver->name }}</th>
                           <td>
                               {!! Form::text('points['.$entrant->id.']', $entrant->points, ['class' => 'form-control']) !!}
                           </td>
                       </tr>
                   @endforeach
                   </tbody>
               </table>
               {!! Form::submit('Update Points', ['class' => 'btn btn-primary']) !!}
               {!! Form::close() !!}
            @else
               No entrants yet - please enter results
            @endif
        </div>
    </div>
</div>
