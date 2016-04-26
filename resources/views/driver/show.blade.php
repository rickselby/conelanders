@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('driver.index') }}">Drivers</a></li>
        <li class="active">{{ $driver->name }}</li>
    </ol>
@endsection

@section('content')

    <h2>Best Results</h2>

    <div class="panel panel-default">
        @include('driver.best-results.championship')
        @include('driver.best-results.season')
        @include('driver.best-results.event')
        @include('driver.best-results.stage')
    </div>

    <h2>All Results</h2>

    @include('driver.all-results.championships')

@endsection