@extends('page')

@section('header')
    <div class="page-header">
        <h1>Add a new stage</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => 'dirt-rally.stage-info.store', 'class' => 'form-horizontal']) !!}

    <div class="alert alert-warning" role="alert">
        The infomation provided here must match the data from the DiRT website <strong>exactly</strong>.
    </div>

    <div class="form-group">
        {!! Form::label('location_name', 'Location Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('location_name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('stage_name', 'Stage Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('stage_name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('dnf_time', 'DNF Time', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('dnf_time', null, ['class' => 'form-control', 'placeholder' => 'mm:ss.xxx']) !!}
            <p class="help-block">
                Time that will be given by the DiRT website if a driver DNFs on this stage
            </p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            {!! Form::submit('Add Stage', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection
