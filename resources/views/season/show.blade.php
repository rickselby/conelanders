@extends('page')

@section('header')
    <div class="page-header">
        <h1>Results: {{ $season->name }}</h1>
    </div>
@endsection

@section('content')

    @if (Auth::user() && Auth::user()->admin)
        {!! Form::open(['route' => ['season.destroy', $season->id], 'method' => 'delete', 'class' => 'form-inline']) !!}
            <a class="btn btn-small btn-warning"
               href="{{ route('season.edit', [$season->id]) }}">Edit season</a>
            {!! Form::submit('Delete Season', array('class' => 'btn btn-danger')) !!}
        {!! Form::close() !!}
    @endif

    <h2>Events</h2>
    @if (Auth::user() && Auth::user()->admin)
        <p>
            <a class="btn btn-small btn-info"
               href="{{ route('season.event.create', [$season->id]) }}">Add a new event</a>
        </p>
    @endif
    <ul>
        @forelse($season->events AS $event)
            <li>
                <a href="{{ route('season.event.show', [$season->id, $event->id]) }}">
                    {{ $event->name }}
                </a>
            </li>
        @empty
            <li>No events</li>
        @endforelse
    </ul>

@endsection