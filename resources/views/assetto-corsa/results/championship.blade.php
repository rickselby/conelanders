@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $championship->name }}</h1>
    </div>
@endsection

@section('content')

    <h4>{{ \ACChampionships::cars($championship)->implode('full_name', ', ') }}</h4>
    @include('assetto-corsa.results.championship-summary')

@endsection
