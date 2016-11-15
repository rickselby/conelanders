@extends('page')

@section('header')
    <div class="page-header">
        <h1>Add another car</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => 'rallycross.car.store', 'class' => 'form-horizontal']) !!}

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
            {!! Form::submit('Add Car', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection
