@extends('page')

@section('content')

    <h2>Entrants</h2>
    {!! Form::open(['route' => ['assetto-corsa.championship.entrants.update', $championship], 'class' => 'form-horizontal']) !!}
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Driver</th>
            <th>Car #</th>
            <th>Primary Colour</th>
            <th>Secondary Colour</th>
            <th>Rookie?</th>
        </tr>
        </thead>
        <tbody>
        @foreach($championship->entrants()->orderByName()->get() as $entrant)
            <tr>
            <div class="row">
                <td>{{ $entrant->driver->name }}</td>
                <td>
                    {!! Form::text('number['.$entrant->id.']', $entrant->number, ['class' => 'form-control']) !!}
                </td>
                <td>
                    {!! Form::text('colour['.$entrant->id.']', $entrant->colour, ['class' => 'form-control']) !!}
                </td>
                <td>
                    {!! Form::text('colour2['.$entrant->id.']', $entrant->colour2, ['class' => 'form-control']) !!}
                </td>
                <td>
                    {!! Form::checkbox('rookie['.$entrant->id.']', 1, $entrant->rookie) !!}
                </td>
            </div>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! Form::submit('Update Entrants', array('class' => 'btn btn-primary')) !!}
    {!! Form::close() !!}

@endsection