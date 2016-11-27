@extends('page')

@section('header')
    <div class="page-header">
        <h1>Add another entrant</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => ['races.championship.entrant.store', $championship], 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('driver', 'Driver', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {{ Form::text('driver', null, ['class' => 'form-control', 'autofocus']) }}
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
            {!! Form::textarea('css', 'background-color: #000000;'."\n".'color: #ffffff;', ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2 col-md-offset-2">
            <span class="badge driver-number entrant-badge" id="entrant-badge">##</span>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('colour', 'Lap Chart Line: Outline', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('colour', '#000000', ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('colour2', 'Lap Chart Line: Inner', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('colour2', '#ffffff', ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-10 col-md-offset-2"">
            <div style="background-color: #ffffff; border-color: #000000;" class="line-example"></div>
        </div>
    </div>
    
    <div class="form-group">
        {!! Form::label('races_team_id', 'Team', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('races_team_id', $championship->teams->sortBy('name')->pluck('name', 'id'), null, ['class' => 'form-control', 'placeholder' => 'No Team']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('races_car_id', 'Car', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('races_car_id', \App\Models\Races\RacesCar::pluck('name', 'id'), null, ['class' => 'form-control', 'placeholder' => 'No Car']) !!}
            <p class="help-block">
                If you want to show a car by the drivers name on the championship summary,
                select it here. If they're in a team, it won't be shown.
            </p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Add Entrant', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

    <script type="text/javascript">
        $(document).ready(function() {

            updateBadgeColour();
            updateLineColour();

            $('#number').bind('input propertychange', function() {
                $('#entrant-badge').html($('#number').val() ? $('#number').val() : '##');
            });

            $('#css').bind('input propertychange', updateBadgeColour);

            $('#colour').bind('input propertychange', updateLineColour);
            $('#colour2').bind('input propertychange', updateLineColour);

            function updateBadgeColour() {
                $('#badge-css').html('.entrant-badge { '+$('#css').val()+' } ');
                return false;
            }

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

    <script type="text/javascript">
        var drivers = new Bloodhound({
            datumTokenizer: function(d) {
                var test = Bloodhound.tokenizers.whitespace(d);
                $.each(test,function(k,v){
                    i = 0;
                    while( (i+1) < v.length ){
                        test.push(v.substr(i,v.length));
                        i++;
                    }
                })
                return test;
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            // `states` is an array of state names defined in "The Basics"
            local: {!! \App\Models\Driver::pluck('name')->toJson() !!}
        });

        $('input[name="driver"]').typeahead({
                    hint: false,
                    highlight: true,
                    minLength: 1
                },
                {
                    name: 'drivers',
                    source: drivers
                });
    </script>

@endsection
