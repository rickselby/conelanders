
<div class="panel {{ !count($entrants) ? 'panel-default' : ($session->show ? 'panel-danger' : 'panel-success') }}">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" href="#results">
                Add a drivers' result
            </a> <span class="caret"></span>
        </h3>
    </div>
    <div class="panel-collapse collapse {{ $session->show || !count($entrants) ? '' : 'in' }}" id="results" role="tabpanel">
        <div class="panel-body">

            @if(count($entrants))

                {!! Form::open(['route' => ['rallycross.championship.event.session.entrant.store', $session->event->championship, $session->event, $session], 'class' => 'form-horizontal']) !!}
                <div class="row">
                    <div class="col-sm-6">
                        <strong>
                            Entrant
                        </strong>
                    </div>
                    <div class="col-sm-1">
                        <strong>
                            Race
                        </strong>
                    </div>
                    <div class="col-sm-1">
                        <strong>
                            DNF?
                        </strong>
                    </div>
                    <div class="col-sm-1">
                        <strong>
                            DSQ?
                        </strong>
                    </div>            </div>
                <div class="row">
                    <div class="col-sm-6">
                        {{ Form::select('entrant', $entrants->pluck('driver.name', 'id'), null, ['class' => 'form-control', 'autofocus']) }}
                    </div>
                    <div class="col-sm-1">
                        {{ Form::text('race', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-sm-1">
                        {{ Form::checkbox('dnf', 1, null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-sm-1">
                        {{ Form::checkbox('dsq', 1, null, ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <strong>
                            Total Time
                        </strong>
                    </div>
                    <div class="col-sm-3">
                        <strong>
                            Penalties?
                        </strong>
                    </div>
                    <div class="col-sm-3">
                        <strong>
                            Fastest Lap
                        </strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        {{ Form::text('time', null, ['placeholder' => 'm:ss.xxx', 'class' => 'form-control']) }}
                    </div>
                    <div class="col-sm-3">
                        {{ Form::text('penalty', null, ['placeholder' => 'm:ss.xxx', 'class' => 'form-control']) }}
                    </div>
                    <div class="col-sm-3">
                        {{ Form::text('lap', null, ['placeholder' => 'm:ss.xxx', 'class' => 'form-control']) }}
                    </div>
                    <div class="col-sm-2">
                        {!! Form::submit('Add Result', array('class' => 'btn btn-primary btn-sm')) !!}
                    </div>
                </div>
                {!! Form::close() !!}

            @else
                No more entrants to add.
            @endif

        </div>
    </div>
</div>
