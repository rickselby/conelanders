@extends('page')

@section('header')
    <div class="page-header">
        <h1>Add a new event</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => ['assetto-corsa.championship.event.store', $championship], 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('time', 'Event Starts', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            <div class='input-group date' id='datetimepicker1'>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                <input name="time" type='text' class="form-control" />
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
            {!! Form::submit('Add Event', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection
