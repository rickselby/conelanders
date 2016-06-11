@extends('page')

@section('content')

    <h2>Roles</h2>

    <p>
        <a class="btn btn-small btn-info" href="{{ route('role.create') }}">Add a new role</a>
    </p>

    <ul>
        @foreach($roles as $role)
            <li>
                <a href="{{ route('role.show', $role) }}">
                    {{ $role->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection