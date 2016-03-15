@extends('page')

@section('header')
    <div class="page-header">
        <h1>Add a new event for the {{ $season->name }} season</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => ['season.event.store', $season->id], 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('dirt_id', 'Dirt Rally ID', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::number('dirt_id', null, ['class' => 'form-control']) !!}
            <p class="help-block">There will be help here for finding the event ID</p>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('datetimepicker', 'End Date', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            <div class='input-group date' id='datetimepicker'>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                <input name="closes" type='text' class="form-control" />
            </div>
            <p class="help-block">Time in UTC</p>
            <script type="text/javascript">
                $(function () {
                    $('#datetimepicker').datetimepicker({
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
