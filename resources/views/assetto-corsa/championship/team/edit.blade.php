@extends('page')

@push('stylesheets')
<link href="{{ route('assetto-corsa.championship.entrant.css', $championship) }}" rel="stylesheet" />
@endpush

@section('header')
    <div class="page-header">
        <h1>Update Team</h1>
    </div>
@endsection

@section('content')

    {!! Form::model($team, ['route' => ['assetto-corsa.championship.team.update', $championship, $team], 'method' => 'put', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Full Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('short_name', 'Short Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('short_name', null, ['class' => 'form-control']) !!}
            <p class="help-block">
                Keep it short, results are already busy...
            </p>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('ac_car_id', 'Car', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('ac_car_id', \App\Models\AssettoCorsa\AcCar::pluck('full_name', 'id'), null, ['class' => 'form-control', 'placeholder' => 'No Car']) !!}
            <p class="help-block">
                If the team is running a single car, pick it from the list
            </p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2 col-md-offset-2">
            <span class="badge driver-number team-{{ $team->id }}">##</span>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('css', 'Badge CSS', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::textarea('css', null, ['class' => 'form-control']) !!}
            <p class="help-block">
                Drivers CSS will override Team CSS
            </p>
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Update Team', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

    {!! Form::open(['route' => ['assetto-corsa.championship.team.destroy', $championship, $team], 'method' => 'delete', 'class' => 'pull-right']) !!}
    {!! Form::submit('Delete Team', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <script type="text/javascript">
        $(document).ready(function() {
            $('#css').bind('input propertychange', function() {
                $('#badge-css').html('.team-{{ $team->id }} { '+$(this).val()+' } ');
                return false;
            });
        })
    </script>
    <style type="text/css" id="badge-css" rel="stylesheet"></style>

@endsection
