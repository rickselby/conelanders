@foreach($signups AS $event)
    <div class="panel {{ $event->selected ? ($event->status ? 'panel-success' : 'panel-warning') : 'panel-danger' }}">
        <div class="panel-heading">
            <h4 class="panel-title">
                Signup for {{ $event->fullName }} ({{ \Times::userTimezone($event->time) }})
            </h4>
        </div>
        <div class="panel-body">
            {!! Form::open(['route' => ['assetto-corsa.championship.event.signup', $event->championship, $event], 'class' => 'form-horizontal']) !!}

            <div class="form-group">
                {!! Form::label('status', 'Will you be in attendance?', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-9">
                    {!! Form::select('status', [1 => 'I am attending', 0 => 'I am NOT attending'], $event->selected ? $event->status : null, ['placeholder' => 'Select Attendance', 'class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    {!! Form::submit('Update Attendance', ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endforeach