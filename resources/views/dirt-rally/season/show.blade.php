@extends('page')

@section('content')

    {!! Form::open(['route' => ['dirt-rally.championship.season.destroy', $season->championship, $season], 'method' => 'delete', 'class' => 'form-inline']) !!}
        <a class="btn btn-small btn-warning"
           href="{{ route('dirt-rally.championship.season.edit', [$season->championship, $season]) }}">Edit season</a>
        {!! Form::submit('Delete Season', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <h2>Events</h2>
    <p>
        <a class="btn btn-small btn-info"
           href="{{ route('dirt-rally.championship.season.event.create', [$season->championship, $season]) }}">Add a new event</a>
    </p>
    <ul>
        @forelse($season->events AS $event)
            <li>
                <a href="{{ route('dirt-rally.championship.season.event.show', [$season->championship, $season, $event]) }}">
                    {{ $event->name }}
                </a>
            </li>
        @empty
            <li>No events</li>
        @endforelse
    </ul>

@endsection