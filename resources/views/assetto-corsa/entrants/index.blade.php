@extends('page')

@section('content')

    <h2>Entrants</h2>
    {!! Form::open(['route' => ['assetto-corsa.championship.entrants.update', $championship], 'class' => 'form-horizontal']) !!}
    <table class="table table-striped">
        <thead>
        <tr>
            <th class="col-md-2">Driver</th>
            <th class="col-md-1">Car #</th>
            <th class="col-md-6">Badge CSS</th>
            <th class="col-md-2">Colours</th>
            <th class="col-md-1">Rookie?</th>
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
                    {!! Form::textarea('css['.$entrant->id.']', $entrant->css, ['class' => 'form-control', 'rows' => '2', 'style' => 'resize:vertical']) !!}
                </td>
                <td>
                    {!! Form::text('colour['.$entrant->id.']', $entrant->colour, ['class' => 'form-control']) !!}
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