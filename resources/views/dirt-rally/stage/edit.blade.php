@extends('page')

@section('header')
    <div class="page-header">
        <h1>Update stage</h1>
    </div>
@endsection

@section('content')

    {!! Form::model($stage, ['route' => ['dirt-rally.championship.season.event.stage.update', $stage->event->season->championship, $stage->event->season, $stage->event, $stage], 'method' => 'put', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('order', 'Order', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::number('order', null, ['class' => 'form-control']) !!}
            <p class="help-block">1st stage = 1, 2nd stage = 2, etc.</p>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('long', 'Long stage?', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::checkbox('long') !!}
            <p class="help-block">Stages are long or short. This changes the time given for a DNF.</p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Update Stage', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection
