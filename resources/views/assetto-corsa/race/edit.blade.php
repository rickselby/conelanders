@extends('page')

@section('header')
    <div class="page-header">
        <h1>Update Race</h1>
    </div>
@endsection

@section('content')

    {!! Form::model($race, ['route' => ['assetto-corsa.championship.race.update', $race->championship, $race], 'method' => 'put', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('time', 'Race Starts', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            <div class='input-group date' id='datetimepicker1'>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                <input name="time" type='text' class="form-control" value="{{ $race->time->format('jS F Y, H:i') }}" />
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
            {!! Form::submit('Update Race', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection