@extends('page')

@section('content')

    {!! Form::open(['route' => ['assetto-corsa.championship.destroy', $championship], 'method' => 'delete', 'class' => 'form-inline']) !!}
        <a class="btn btn-small btn-warning"
           href="{{ route('assetto-corsa.championship.edit', $championship) }}">Edit championship</a>
        {!! Form::submit('Delete championship', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <h2>Entrants</h2>
    <p>
        <a class="btn btn-small btn-primary"
           href="{{ route('assetto-corsa.championship.entrants.index', $championship) }}">Manage Entrants</a>
    </p>

    <h2>Races</h2>
    <p>
        <a class="btn btn-small btn-info"
           href="{{ route('assetto-corsa.championship.race.create', $championship) }}">Add a new race</a>
    </p>

    <ul>
        @forelse($championship->races AS $race)
            <li>
                <a href="{{ route('assetto-corsa.championship.race.show', [$championship, $race]) }}">
                    {{ $race->name }}
                </a>
            </li>
        @empty
            <li>No events</li>
        @endforelse
    </ul>

@endsection