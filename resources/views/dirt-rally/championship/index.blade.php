@extends('page')

@section('content')

    <p>
        <a class="btn btn-small btn-info" href="{{ route('dirt-rally.championship.create') }}">Add a new championship</a>
    </p>

    <h2>Championships</h2>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Name</th>
            <th>Seasons</th>
            <th>Complete?</th>
        </tr>
        </thead>
        <tbody>
        @forelse($championships AS $championship)
            <tr class="{{ $championship->isComplete() ? '' : 'info' }}">
                <td>
                    <a href="{{ route('dirt-rally.championship.show', $championship) }}">
                        {{ $championship->name }}
                    </a>
                </td>
                <td>{{ count($championship->seasons) }}</td>
                <td>{{ $championship->isComplete() ? 'Yes' : 'No' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No championships</td>
            </tr>
        @endforelse
        </tbody>
    </table>


@endsection