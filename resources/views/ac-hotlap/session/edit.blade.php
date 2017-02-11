@extends('page')

@section('header')
    <div class="page-header">
        <h1>Update session</h1>
    </div>
@endsection

@section('content')

    {!! Form::model($session, ['route' => ['assetto-corsa.hotlaps.session.update', $session], 'method' => 'put', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Track', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('start', 'Session Starts', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            <div class='input-group date' id='datetimepicker1'>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                {!! Form::text('start', null, ['class' => 'form-control']) !!}
            </div>
            <script type="text/javascript">
                $(function () {
                    $('#datetimepicker1').datetimepicker({
                        sideBySide: true,
                        format: "Do MMMM YYYY"
                    });
                });
            </script>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('finish', 'Session Finishes', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            <div class='input-group date' id='datetimepicker2'>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                {!! Form::text('finish', null, ['class' => 'form-control']) !!}
            </div>
            <script type="text/javascript">
                $(function () {
                    $('#datetimepicker2').datetimepicker({
                        sideBySide: true,
                        format: "Do MMMM YYYY"
                    });
                });
            </script>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('cars', 'Cars', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            <ul class="list-group">
                @foreach($cars AS $car)
                <li class="list-group-item">
                    {!! Form::checkbox('car['.$car->id.']', 1, $session->cars->contains('id', $car->id)) !!}
                    {{ $car->name }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Update Session', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection
