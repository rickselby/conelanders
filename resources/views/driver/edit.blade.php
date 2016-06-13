@extends('page')

@section('header')
    <div class="page-header">
        <h1>Update driver</h1>
    </div>
@endsection

@section('content')

    {!! Form::model($driver, ['route' => ['driver.update', $driver], 'method' => 'put', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('nation_id', 'Nation', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('nation_id', $nations->sortBy('name')->pluck('name', 'id'), null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('dirt_racenet_driver_id', 'Racenet ID', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::number('dirt_racenet_driver_id', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('ac_guid', 'Steam ID', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::number('ac_guid', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('locked', 'Disallow updates from imports', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::checkbox('locked', 1, $driver->locked) !!}
            <p class="help-block">
                If you edit anything here, tick this box to stop imports (from racenet) overriding the data.
            </p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Update Driver', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

    {!! Form::open(['route' => ['driver.destroy', $driver], 'method' => 'delete', 'class' => 'pull-right']) !!}
        {!! Form::submit('Delete driver', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

@endsection
