@extends('page')

@section('content')

    {!! Form::open(['route' => ['races.championship.event.save-entrants', $event->championship, $event], 'class' => 'form-horizontal']) !!}
    {!! Form::hidden('results_type', session('results_type')) !!}
    <h2>Name Cars</h2>
    @foreach($entrants['cars'] AS $car)
        <div class="form-group">
            {!! Form::label('car['.$car.']', $car, ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::text('car['.$car.']', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    @endforeach

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Confirm Names', array('class' => 'btn btn-primary')) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection