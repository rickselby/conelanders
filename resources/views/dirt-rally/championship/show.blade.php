@extends('page')

@section('content')

    {!! Form::open(['route' => ['dirt-rally.championship.destroy', $championship], 'method' => 'delete', 'class' => 'form-inline']) !!}
        <a class="btn btn-small btn-warning"
           href="{{ route('dirt-rally.championship.edit', $championship) }}">Edit championship</a>
        {!! Form::submit('Delete championship', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <h2>Seasons</h2>
    <p>
        <a class="btn btn-small btn-info"
           href="{{ route('dirt-rally.championship.season.create', $championship) }}">Add a new season</a>
    </p>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Name</th>
            <th>Events</th>
            <th>Complete?</th>
        </tr>
        </thead>
        <tbody>
        @forelse($seasons AS $season)
            <tr class="{{ $season->isComplete() ? '' : 'info' }}">
                <td>
                    <a href="{{ route('dirt-rally.championship.season.show', [$championship, $season]) }}">
                        {{ $season->name }}
                    </a>
                </td>
                <td>{{ count($season->events) }}</td>
                <td>{{ $season->isComplete() ? 'Yes' : 'No' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No seasons</td>
            </tr>
        @endforelse
        </tbody>
    </table>

@endsection