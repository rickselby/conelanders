@extends('page')

@section('header')
    <div class="page-header">
        <h1>Add a new stage to {{ $event->name }} ({{ $event->season->name }})</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => ['season.event.stage.store', $event->season->id, $event->id], 'class' => 'form-horizontal']) !!}

    {!!  Form::hidden('eventID', $event->id) !!}

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
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Add Stage', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection
