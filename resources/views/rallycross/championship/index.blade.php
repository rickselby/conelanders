@extends('page')

@section('header')
    <div class="page-header">
        <h1>Championships</h1>
    </div>
@endsection

@section('content')

    <p>
        <a class="btn btn-small btn-info" href="{{ route('rallycross.championship.create') }}">Add a new championship</a>
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
        @forelse($championships AS $championship)
            <tr class="{{ $championship->isComplete() ? '' : 'info' }}">
                <td>
                    <a href="{{ route('rallycross.championship.show', $championship) }}">
                        {{ $championship->name }}
                    </a>
                </td>
                <td>{{ count($championship->events) }}</td>
                <td>{{ $championship->isComplete() ? 'Yes' : 'No' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No championships</td>
            </tr>
        @endforelse
        </tbody>
    </table>

@endsection