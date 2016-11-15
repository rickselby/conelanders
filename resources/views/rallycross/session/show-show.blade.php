
<div class="panel panel-default {{ $session->show ? 'panel-success' : 'panel-danger' }}">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" href="#results">
                Results Complete
            </a> <span class="caret"></span>
        </h3>
    </div>
    <div class="panel-collapse collapse {{ $session->show ? '' : 'in' }}" id="results" role="tabpanel">
        <div class="panel-body">
            {!! Form::open(['route' => ['rallycross.championship.event.session.complete', $session->event->championship, $session->event, $session], 'method' => 'get', 'class' => 'form-horizontal']) !!}
                {!! Form::submit('Mark the results as Complete', array('class' => 'btn btn-primary')) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
