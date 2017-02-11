
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" href="#results">
                Add a drivers' result
            </a> <span class="caret"></span>
        </h3>
    </div>
    <div class="panel-collapse collapse in" id="results" role="tabpanel">
        <div class="panel-body">

            {!! Form::open(['route' => ['assetto-corsa.hotlaps.session.entrant.store', $session], 'class' => 'form-horizontal']) !!}

            <div class="form-group">
                {!! Form::label('driver', 'Driver', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('driver', null, ['class' => 'form-control', 'autofocus']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('car', 'Car', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::select('car', $session->cars->pluck('name', 'id'), null, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('time', 'Total Time', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('time', null, ['class' => 'form-control', 'placeholder' => 'm:ss.xxx']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('sectors', 'Sector Times', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('sectors', null, ['class' => 'form-control', 'placeholder' => 'm:ss.xxx,m:ss.xxx,m:ss.xxx...']) !!}
                    <p class="help-block">
                        Leave blank if not available. Otherwise, separate times with a comma.
                    </p>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    {!! Form::submit('Add Lap', array('class' => 'btn btn-primary btn-sm')) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

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

</div>
