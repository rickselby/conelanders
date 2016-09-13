@extends('page')

@section('content')

    {!! Form::open(['route' => ['assetto-corsa.championship.destroy', $championship], 'method' => 'delete', 'class' => 'form-inline']) !!}
        <a class="btn btn-small btn-warning"
           href="{{ route('assetto-corsa.championship.edit', $championship) }}">Edit championship</a>
        {!! Form::submit('Delete championship', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <h2>Entrants</h2>

    <div class="btn-group" role="group">
        <a class="btn btn-small btn-primary"
           href="{{ route('assetto-corsa.championship.entrant.index', $championship) }}">Manage Entrants</a>
        <a class="btn btn-small btn-primary"
           href="{{ route('assetto-corsa.championship.team.index', $championship) }}">Manage Teams</a>
    </div>

    <h2>Events</h2>
    <p>
        <a class="btn btn-small btn-info"
           href="{{ route('assetto-corsa.championship.event.create', $championship) }}">Add a new event</a>
    </p>

    <ul class="list-group">
        @forelse($championship->events AS $event)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-xs-6 col-md-3">
                        <a href="{{ route('assetto-corsa.championship.event.show', [$championship, $event]) }}">
                            {{ $event->name }}
                        </a>
                    </div>
                    <div class="col-xs-6 col-md-9">
                        {{ \Times::userTimezone($event->time) }}
                    </div>
                </div>
            </li>
        @empty
            <li>No events</li>
        @endforelse
    </ul>

@endsection