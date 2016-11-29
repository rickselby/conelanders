@extends('page')

@push('stylesheets')
<link href="{{ route('races.championship.entrant.css', $championship) }}" rel="stylesheet" />
@endpush

@section('header')
    <div class="page-header">
        <h1>{{ $championship->name }}: Teams</h1>
    </div>
@endsection

@section('content')
    <p>
        <a class="btn btn-small btn-info" href="{{ route('races.championship.team.create', $championship) }}">Add another team</a>
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
                       href="{{ route('races.championship.team.edit', [$championship, $team]) }}">Edit Team</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection