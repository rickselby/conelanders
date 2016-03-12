@extends('page')

@section('header')
    <div class="page-header">
        <h1>Add a new event for the {{ $season->name }} season</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => 'event.store', 'class' => 'form-horizontal']) !!}

    {!!  Form::hidden('seasonID', $season->id) !!}

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
            <p class="help-block">Some help here...</p>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('closes', 'End Date', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::date('closes', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
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
