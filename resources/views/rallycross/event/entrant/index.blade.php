@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $event->championship->name }}: {{ $event->name }}: Entrants</h1>
    </div>
@endsection

@section('content')

    <p>
        <a class="btn btn-small btn-info" href="{{ route('rallycross.championship.event.entrant.create', [$event->championship, $event]) }}">Add another entrant</a>
    </p>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Driver</th>
            <th>Car</th>
            <th class="col-md-3"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($event->entrants()->with('driver.nation')->orderByName()->get() as $entrant)
            <tr>
                <td>
                    {{ $entrant->driver->name }}
                </td>
                <td>
                    {{ $entrant->car ? $entrant->car->name : '-' }}
                </td>
                <td>
                    {!! Form::open(['route' => ['rallycross.championship.event.entrant.destroy', $event->championship, $event, $entrant], 'method' => 'delete']) !!}
                    {!! Form::submit('Delete Entrant', array('class' => 'btn btn-danger btn-xs')) !!}
                    <a class="btn btn-xs btn-info"
                       href="{{ route('driver.edit', $entrant->driver) }}">Edit Driver</a>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


@endsection