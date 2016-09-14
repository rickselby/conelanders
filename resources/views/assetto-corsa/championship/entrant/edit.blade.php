@extends('page')

@push('stylesheets')
<link href="{{ route('assetto-corsa.championship.entrant.css', $championship) }}" rel="stylesheet" />
@endpush

@section('header')
    <div class="page-header">
        <h1>Update Entrant</h1>
    </div>
@endsection

@section('content')

    {!! Form::model($entrant, ['route' => ['assetto-corsa.championship.entrant.update', $championship, $entrant], 'method' => 'put', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('driver_id', 'Driver', ['class' => 'col-sm-2 control-label']) !!}
        {!! Form::hidden('driver_id') !!}
        <div class="col-sm-3">
            @include('assetto-corsa.driver.name', ['entrant' => $entrant])
        </div>
        <div class="col-sm-3">
            <div style="background-color: {{ $entrant->colour2 }}; border-color: {{ $entrant->colour }};" class="line-example"></div>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('rookie', 'Rookie', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::checkbox('rookie', 1) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('number', 'Car Number', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('number', null, ['class' => 'form-control']) !!}
            <p class="help-block">
                (is a string, so you can enter 06, if you want)
            </p>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('css', 'Badge CSS', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::textarea('css', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('colour', 'Lap Chart Line: Outline', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('colour', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('colour2', 'Lap Chart Line: Inner', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('colour2', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('ac_team_id', 'Team', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('ac_team_id', $championship->teams->sortBy('name')->pluck('name', 'id'), null, ['class' => 'form-control', 'placeholder' => 'No Team']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('ac_car_id', 'Car', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('ac_car_id', \App\Models\AssettoCorsa\AcCar::pluck('name', 'id'), null, ['class' => 'form-control', 'placeholder' => 'No Car']) !!}
            <p class="help-block">
                If you want to show a car by the drivers name on the championship summary,
                select it here. If they're in a team, it won't be shown.
            </p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Update Entrant', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

    {!! Form::open(['route' => ['assetto-corsa.championship.entrant.destroy', $championship, $entrant], 'method' => 'delete', 'class' => 'pull-right']) !!}
    {!! Form::submit('Delete Entrant', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <script type="text/javascript">
        $(document).ready(function() {
            $('#number').bind('input propertychange', function() {
                $('.entrant-{{ $entrant->id }}').html($('#number').val() ? $('#number').val() : '##');
            });

            $('#css').bind('input propertychange', function() {
                $('#badge-css').html('.entrant-{{ $entrant->id }} { '+$(this).val()+' } ');
                return false;
            });

            $('#colour').bind('input propertychange', updateLineColour);
            $('#colour2').bind('input propertychange', updateLineColour);

            function updateLineColour() {
                $('.line-example').css({
                        backgroundColor: $('#colour2').val(),
                        borderColor: $('#colour').val()
                });
                return false;
            }
        })
    </script>
    <style type="text/css" id="badge-css" rel="stylesheet"></style>

@endsection
