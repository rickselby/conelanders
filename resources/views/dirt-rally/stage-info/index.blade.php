@extends('page')

@section('content')

    <h2>Stage Management</h2>

    <p>
        <a class="btn btn-small btn-info" href="{{ route('dirt-rally.stage-info.create') }}">Add another stage</a>
    </p>

    <table class="table table-hover table-striped">
        <thead>
        <tr>
            <th>Location</th>
            <th>Stage Name</th>
            <th>DNF Time</th>
            <th>Used</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($stages AS $stage)
            <tr>
                <td>
                    {{ $stage->location_name }}
                </td>
                <td>
                    {{ $stage->stage_name }}
                </td>
                <td>
                    {{ \Times::toString($stage->dnf_time) }}
                </td>
                <td>
                    {{ count($stage->stages) }}
                </td>
                <td>
                    <a class="btn btn-warning btn-xs" href="{{ route('dirt-rally.stage-info.edit', $stage) }}">Edit</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No championships</td>
            </tr>
        @endforelse
        </tbody>
    </table>


@endsection