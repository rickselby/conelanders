@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('driver.index') }}">Drivers</a></li>
        <li class="active">{{ $driver->name }}</li>
    </ol>
@endsection

@section('content')

    <h2>Results</h2>

    @include('driver.all-results')

@endsection