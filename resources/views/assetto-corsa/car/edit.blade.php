@extends('page')

@section('header')
    <div class="page-header">
        <h1>Update car</h1>
    </div>
@endsection

@section('content')

    {!! Form::model($car, ['route' => ['assetto-corsa.car.update', $car], 'method' => 'put', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('ac_identifier', 'Assetto Corsa Identifier', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('ac_identifier', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('name', 'Full Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
            <p class="help-block">
                Full name of the car
            </p>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('short_name', 'Short Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('short_name', null, ['class' => 'form-control']) !!}
            <p class="help-block">
                Keep it short, results are already busy...
            </p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Update Car', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

    {!! Form::open(['route' => ['assetto-corsa.car.destroy', $car], 'method' => 'delete', 'class' => 'pull-right']) !!}
    {!! Form::submit('Delete Car', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

@endsection
