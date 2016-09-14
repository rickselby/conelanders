@extends('page')

@push('stylesheets')
<link href="{{ route('assetto-corsa.championship.entrant.css', $championship) }}" rel="stylesheet" />
@endpush

@section('content')

    <h2>Teams</h2>

    <p>
        <a class="btn btn-small btn-info" href="{{ route('assetto-corsa.championship.team.create', $championship) }}">Add another team</a>
    </p>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Team Name</th>
            <th>Short Name</th>
            <th>Car</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($championship->teams->sortBy('name') as $team)
            <tr>
                <td>
                    <span class="badge driver-number team-{{ $team->id }}">##</span>
                    {{ $team->name }}
                </td>
                <td>
                    {{ $team->short_name }}
                </td>
                <td>
                    {{ $team->car ? $team->car->name : '-' }}
                </td>
                <td>
                    <a class="btn btn-xs btn-warning"
                       href="{{ route('assetto-corsa.championship.team.edit', [$championship, $team]) }}">Edit Team</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection