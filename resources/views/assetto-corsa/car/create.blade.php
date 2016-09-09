@extends('page')

@section('header')
    <div class="page-header">
        <h1>Add another car</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => 'assetto-corsa.car.store', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('ac_identifier', 'Assetto Corsa Identifier', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('ac_identifier', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('full_name', 'Full Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('full_name', null, ['class' => 'form-control']) !!}
            <p class="help-block">
                Full name of the car
            </p>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('name', 'Short Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
            <p class="help-block">
                Keep it short, results are already busy...
            </p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Add Car', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection
