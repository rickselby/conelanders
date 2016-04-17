@extends('page')

@section('header')
    <div class="page-header">
        <h1>Add a new nation</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => 'nation.store', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('acronym', 'Acronym', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('acronym', null, ['class' => 'form-control']) !!}
            <p class="help-block">
                Three-letter country code
            </p>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('dirt_reference', 'Dirt Reference', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::number('dirt_reference', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Add Nation', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection
