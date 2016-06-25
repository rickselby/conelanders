@extends('page')

@section('content')

    <h2>Pending User/Driver Assignments</h2>

    <table class="table">
        <thead>
        <tr>
            <th>User's email</th>
            <th>Driver Name</th>
            <th>Confirm?</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users AS $user)
            <tr>
                <td>{{ $user->email }}</td>
                <td>{{ $user->driver->name }}</td>
                <td>
                    <a class="btn btn-success" href="{{ route('driver.assign', $user) }}">Confirm</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


@endsection