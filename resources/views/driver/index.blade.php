@extends('page')

@section('content')

    <h2>Drivers</h2>

    <div class="form-horizontal">
        @foreach($drivers as $driver)
        <div class="form-group">
            <div class="col-sm-6 col-md-4 control-label">
                {{ $driver->name }}
                @include('nation.image', ['nation' => $driver->nation])
            </div>
            <div class="col-sm-6 col-md-8">
                <a class="btn btn-success" href="{{ route('driver.show', $driver) }}">View</a>
                @can('driver-admin')
                    <a class="btn btn-warning" href="{{ route('driver.edit', $driver) }}">Edit</a>
                @endcan
            </div>
        </div>
        @endforeach
    </div>

@endsection