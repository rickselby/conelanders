@extends('page')

@section('header')
    <div class="page-header">
        <h1>Update event</h1>
    </div>
@endsection

@section('content')

    {!! Form::model($event, ['route' => ['dirt-rally.championship.season.event.update', $event->season->championship, $event->season, $event], 'method' => 'put', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('racenet_event_id', 'Dirt Rally ID', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::number('racenet_event_id', null, ['class' => 'form-control']) !!}
            <p class="help-block">
                <a target="_blank" href="{{ route('dirt-rally.event-id-help') }}">View help for finding the event ID</a>
            </p>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('opens', 'Event Opens', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            <div class='input-group date' id='datetimepicker1'>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                <input name="opens" type='text' class="form-control" value="{{ $event->opens->format('jS F Y, H:i') }}" />
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
        {!! Form::label('closes', 'End Date', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            <div class='input-group date' id='datetimepicker2'>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                <input name="closes" type='text' class="form-control" value="{{ $event->closes->format('jS F Y, H:i') }}" />
            </div>
            <p class="help-block">Time in UTC</p>
            <script type="text/javascript">
                $(function () {
                    $('#datetimepicker2').datetimepicker({
                        sideBySide: true,
                        format: "Do MMMM YYYY, HH:mm"
                    });
                });
            </script>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('playlistLink', 'Youtube Playlist', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('playlistLink', $event->playlist ? $event->playlist->link : '', ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Update Event', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection
