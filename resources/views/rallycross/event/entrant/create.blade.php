@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $event->championship->name }}: {{ $event->name }}: Add Entrant</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => ['rallycross.championship.event.entrant.store', $event->championship, $event], 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('driver', 'Driver', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {{ Form::text('driver', null, ['class' => 'form-control', 'autofocus']) }}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('car', 'Car', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('car', \App\Models\RallyCross\RxCar::orderBy('name')->pluck('name', 'id'), null, ['class' => 'form-control']) !!}
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
