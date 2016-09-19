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

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Name</th>
            <th>Sessions</th>
            <th>Scheduled Time</th>
            <th>Full Results Released?</th>
        </tr>
        </thead>
        <tbody>
        @forelse($championship->events AS $event)
            <tr class="{{ $event->canBeReleased() ? '' : 'info' }}">
                <td>
                    <a href="{{ route('assetto-corsa.championship.event.show', [$championship, $event]) }}">
                        {{ $event->name }}
                    </a>
                </td>
                <td>{{ count($event->sessions) }}</td>
                <td>{{ \Times::userTimezone($event->time) }}</td>
                <td>
                    @if ($event->canBeReleased())
                        Yes
                    @elseif ($event->completeAt)
                        {{ \Times::userTimezone($event->completeAt) }}
                    @else
                        No
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No events</td>
            </tr>
        @endforelse
        </tbody>
    </table>

@endsection