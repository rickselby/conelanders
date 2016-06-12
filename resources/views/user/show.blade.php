@extends('page')

@section('content')

    @if ($user->driver)

        @if ($user->driver_confirmed)

            idk?

        @else
            <div class="panel panel-info">
                <div class="panel-body">
                    Your driver selection has been recorded. Go and pester dJomp on discord, reddit or YouTube
                    and he'll confirm the link.
                </div>
            </div>
        @endif

    @else

        <p>
            You are not linked to a driver yet. Please select a driver below:
        </p>

        {!! Form::open(['route' => 'user.select-driver', 'class' => 'form-horizontal']) !!}
        <div class="form-group">
            <div class="col-sm-4">
                {!! Form::select('driver', $drivers->pluck('name', 'id'), null, ['class' => 'form-control']) !!}
            </div>
            <div class="col-sm-8">
                {!! Form::submit('This is me', array('class' => 'btn btn-success')) !!}
            </div>
        </div>
        {!! Form::close() !!}

    @endif

@endsection