@extends('page')

@section('content')

    {!! Form::open(['route' => ['assetto-corsa.championship.event.destroy', $event->championship, $event], 'method' => 'delete', 'class' => 'form-inline']) !!}
        <a class="btn btn-small btn-warning"
           href="{{ route('assetto-corsa.championship.event.edit', [$event->championship, $event]) }}">Edit Event</a>
        {!! Form::submit('Delete Event', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <br />

    <h2>Sessions</h2>
    <p>
        <a class="btn btn-small btn-info"
           href="{{ route('assetto-corsa.championship.event.session.create', [$event->championship, $event]) }}">Add a new session</a>
    </p>

    <ul>
        @forelse($event->sessions AS $session)
            <li>
                <a href="{{ route('assetto-corsa.championship.event.session.show', [$event->championship, $event, $session]) }}">
                    {{ $session->name }}
                </a>
            </li>
        @empty
            <li>No sessions</li>
        @endforelse
    </ul>

    <div class="panel {{ ($event->release) ? 'panel-success' : 'panel-danger' }}">
        <div class="panel-heading">
            <h3 class="panel-title">
                <a role="button" data-toggle="collapse" href="#releaseDate">
                    Release Date
                </a> <span class="caret"></span>
            </h3>
        </div>
        <div class="panel-collapse collapse {{ ($event->release) ? '' : 'in' }}" id="releaseDate" role="tabpanel">
            <div class="panel-body">
                {!! Form::open(['route' => ['assetto-corsa.championship.event.release-date', $event->championship, $event], 'method' => 'put', 'class' => 'form-horizontal']) !!}

                <div class="form-group">
                    {!! Form::label('release', 'Release Results At:', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        <div class='input-group date' id='datetimepicker1'>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input name="release" type='text' class="form-control" value="{{ $event->release ? $event->release->format('jS F Y, H:i') : '' }}" />
                        </div>
                        <p class="help-block">Time in UTC</p>
                        <script type="text/javascript">
                            $(function () {
                                $('#datetimepicker1').datetimepicker({
                                    sideBySide: true,
                                    format: "Do MMMM YYYY, HH:mm"
                                });
                            });
                        </script>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                        {!! Form::submit('Update Release Date', ['class' => 'btn btn-primary']) !!}
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection