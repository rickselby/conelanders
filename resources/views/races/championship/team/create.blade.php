@extends('page')

@section('header')
    <div class="page-header">
        <h1>Add another team</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => ['races.championship.team.store', $championship], 'class' => 'form-horizontal']) !!}

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
        {!! Form::label('races_car_id', 'Car', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('races_car_id', \App\Models\Races\RacesCar::pluck('name', 'id'), null, ['class' => 'form-control', 'placeholder' => 'No Car']) !!}
            <p class="help-block">
                If the team is running a single car, pick it from the list
            </p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2 col-md-offset-2">
            <span class="badge driver-number team-sample">##</span>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('css', 'Badge CSS', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::textarea('css', 'background-color: #000000;'."\n".'color: #ffffff;', ['class' => 'form-control']) !!}
            <p class="help-block">
                Drivers CSS will override Team CSS
            </p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Add Team', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

    <script type="text/javascript">
        $(document).ready(function() {

            setBadgeCSS();

            $('#css').bind('input propertychange', setBadgeCSS);

            function setBadgeCSS()
            {
                $('#badge-css').html('.team-sample { '+$('#css').val()+' } ');
                return false;
            }
        })
    </script>
    <style type="text/css" id="badge-css" rel="stylesheet"></style>

@endsection
