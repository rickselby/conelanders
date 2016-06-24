@extends('page')

@section('content')

    {!! Form::open(['route' => ['role.destroy', $role], 'method' => 'delete', 'class' => 'form-inline']) !!}
        <a class="btn btn-small btn-warning"
           href="{{ route('role.edit', $role) }}">Edit Role</a>
        {!! Form::submit('Delete Role', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <h2>Role permissions</h2>
    <h3>Add another permission</h3>

    @if ($permissions->count())
    {!! Form::open(['route' => ['role.add-permission', $role], 'class' => 'form-horizontal']) !!}
    <div class="form-group">
        <div class="col-sm-4">
            {!! Form::select('permission', $permissions->pluck('name', 'id'), null, ['class' => 'form-control']) !!}
        </div>
        <div class="col-sm-8">
            {!! Form::submit('Add permission to this role', array('class' => 'btn btn-success')) !!}
        </div>
    </div>
    {!! Form::close() !!}
    @else
        <p>No permissions left to add</p>
    @endif

    <h3>Current Permissions</h3>

    @foreach($role->permissions AS $permission)
        {!! Form::open(['route' => ['role.remove-permission', $role, $permission], 'method' => 'delete', 'class' => 'form-horizontal']) !!}
        <div class="form-group">
            <div class="col-sm-4 control-label">{{ $permission->name }}</div>
            <div class="col-sm-8">
                {!! Form::submit('Remove Permission', ['class' => 'btn btn-danger']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    @endforeach

    <h2>Users</h2>
    <h3>Add another user</h3>

    @if ($users->count())
    {!! Form::open(['route' => ['role.add-user', $role], 'class' => 'form-horizontal']) !!}
    <div class="form-group">
        <div class="col-sm-4">
            {!! Form::select('user', $users->pluck('name', 'id'), null, ['class' => 'form-control']) !!}
        </div>
        <div class="col-sm-8">
            {!! Form::submit('Add user to this role', array('class' => 'btn btn-success')) !!}
        </div>
    </div>
    {!! Form::close() !!}
    @else
        <p>No users left to add</p>
    @endif

    <h3>Current Users</h3>

    @foreach($role->users AS $user)
    {!! Form::open(['route' => ['role.remove-user', $role, $user], 'method' => 'delete', 'class' => 'form-horizontal']) !!}
    <div class="form-group">
        <div class="col-sm-4 control-label">{{ $user->name }}</div>
        <div class="col-sm-8">
            {!! Form::submit('Remove User', ['class' => 'btn btn-danger']) !!}
        </div>
    </div>
    {!! Form::close() !!}
    @endforeach

@endsection