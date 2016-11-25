@extends('page')

@section('content')

    @if (!$user->driver || !$user->driver_confirmed)
        <h2>Link your account to your Driver</h2>

        @if ($user->driver && !$user->driver_confirmed)
            <div class="panel panel-info">
                <div class="panel-body">
                    Your driver selection has been recorded. Go and pester dJomp on discord, reddit or YouTube
                    and he'll confirm the link.
                </div>
            </div>
        @elseif (!$user->driver)

            <p>
                You are not linked to a driver yet. Please select yourself from the list:
            </p>

            {!! Form::open(['route' => 'user.select-driver', 'class' => 'form-horizontal']) !!}
            <div class="form-group">
                {!! Form::label('driver', 'Driver', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::select('driver', $drivers->pluck('name', 'id'), null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    {!! Form::submit('This is me', array('class' => 'btn btn-success')) !!}
                </div>
            </div>
            {!! Form::close() !!}
        @endif

    @endif

    <h2>Profile</h2>

    {!! Form::open(['route' => 'user.update-profile', 'class' => 'form-horizontal']) !!}
    <div class="form-group">
        {!! Form::label('timezone', 'Timezone', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Timezone::selectForm($user->timezone ?: 'UTC', null, array('class' => 'form-control', 'name' => 'timezone')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            {!! Form::submit('Update Profile', array('class' => 'btn btn-success')) !!}
        </div>
    </div>
    {!! Form::close() !!}


@endsection
