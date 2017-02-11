@extends('page')

@section('content')

    <p>
        <a class="btn btn-small btn-info" href="{{ route('assetto-corsa.hotlaps.session.create') }}">Add a new session</a>
    </p>

    <h2>Sessions</h2>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Track</th>
            <th>Cars</th>
            <th>Start</th>
            <th>Finish</th>
        </tr>
        </thead>
        <tbody>
        @forelse($sessions AS $session)
            <tr class="{{ $session->isComplete() ? '' : 'info' }}">
                <td>
                    <a href="{{ route('assetto-corsa.hotlaps.session.show', $session) }}">
                        {{ $session->name }}
                    </a>
                </td>
                <td>{{ $session->cars->pluck('short_name')->implode(', ') }}</td>
                <td>{{ $session->start->format('Y-m-d') }}</td>
                <td>{{ $session->finish->format('Y-m-d') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No sessions</td>
            </tr>
        @endforelse
        </tbody>
    </table>


@endsection