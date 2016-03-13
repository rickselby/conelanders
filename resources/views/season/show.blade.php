@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $season->name }} Season</h1>
    </div>
@endsection

@section('content')

    <h2>Events</h2>
    <ul>
        @forelse($season->events AS $event)
            <li>
                <a href="{{ route('season.event.show', ['season' => $season->id, 'event' => $event->id]) }}">
                    {{ $event->name }}
                </a>
            </li>
        @empty
            <li>No events</li>
        @endforelse
    </ul>

    <a class="btn btn-small btn-info" href="{{ route('season.event.create', ['seasonID' => $season->id]) }}">Add a new event</a>

@endsection