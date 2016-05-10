@extends('page')

@section('header')
    <div class="page-header">
        <h1>Add a new points system</h1>
    </div>
@endsection

@section('content')

    {!! Form::model($system, ['route' => ['assetto-corsa.points-system.update', $system], 'method' => 'put', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('race_points_sequence', 'Race Points Sequence', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('race_points_sequence', $sequences, null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('laps_points_sequence', 'Laps Points Sequence', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('laps_points_sequence', $sequences, null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('default', 'Default System?', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::checkbox('default') !!}
            <p class="help-block">Select one system to be the default</p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Update Points System', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection
