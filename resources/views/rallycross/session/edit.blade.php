@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $session->event->championship->name }}: {{ $session->event->name }}: {{ $session->name }}</h1>
    </div>
@endsection

@section('content')

    {!! Form::model($session, ['route' => ['rallycross.championship.event.session.update', $session->event->championship, $session->event, $session], 'method' => 'put', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('heat', 'Heat?', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::checkbox('heat') !!}
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
