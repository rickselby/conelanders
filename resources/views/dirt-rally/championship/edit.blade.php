@extends('page')

@section('header')
    <div class="page-header">
        <h1>Update championship</h1>
    </div>
@endsection

@section('content')

    {!! Form::model($championship, ['route' => ['dirt-rally.championship.update', $championship], 'method' => 'put', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('event_points_sequence', 'Event Points Sequence', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('event_points_sequence', $sequences, null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('stage_points_sequence', 'Stage Points Sequence', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('stage_points_sequence', $sequences, null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Update Championship', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection
