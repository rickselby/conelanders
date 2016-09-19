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

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Name</th>
            <th>Stages</th>
            <th>Complete?</th>
        </tr>
        </thead>
        <tbody>
        @forelse($season->events AS $event)
            <tr class="{{ $event->isComplete() ? '' : 'info' }}">
                <td>
                    <a href="{{ route('dirt-rally.championship.season.event.show', [$season->championship, $season, $event]) }}">
                        {{ $event->name }}
                    </a>
                </td>
                <td>{{ count($event->stages) }}</td>
                <td>{{ $event->isComplete() ? 'Yes' : 'No' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No events</td>
            </tr>
        @endforelse
        </tbody>
    </table>

@endsection