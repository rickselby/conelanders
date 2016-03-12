@extends('page')

@section('header')
    <div class="page-header">
        <h1>Add a new season</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => 'season.store', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-4 control-label']) !!}
        <div class="col-sm-8">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-4"></div>
        <div class="col-sm-8">
            {!! Form::submit('Add Season', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection
