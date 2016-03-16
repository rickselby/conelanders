@extends('page')

@section('header')
    <div class="page-header">
        <h1>Update season</h1>
    </div>
@endsection

@section('content')

    {!! Form::model($season, ['route' => ['season.update', $season->id], 'method' => 'put', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Update Season', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection
