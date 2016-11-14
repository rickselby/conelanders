@extends('page')

@section('header')
    <div class="page-header">
        <h1>Add a new championship</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => 'races.championship.store', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('drop_events', 'Event scores to drop', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::number('drop_events', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('constructors_count', 'Calculate Constructors', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('constructors_count', \RacesConstructorStandings::getOptions(), null, ['class' => 'form-control']) !!}
            <p class="help-block">
                If you want averages, use <em>session average</em> where possible.
                However, this will give wildly different results if entrants don't enter every session in an event (e.g. season 3).
                In those cases, use <em>event average</em>.
            </p>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('teams_count', 'Calculate Teams', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('teams_count', \RacesConstructorStandings::getOptions(), null, ['class' => 'form-control']) !!}
            <p class="help-block">
                See above for average advice.
            </p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Add Championship', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection
