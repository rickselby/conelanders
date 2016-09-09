@extends('page')

@section('header')
    <div class="page-header">
        <h1>Assetto Corsa</h1>
    </div>
@endsection

@section('content')
    @foreach($championships AS $championship)

        <h2>{{ $championship->name }}</h2>
        <h4>{{ \ACChampionships::cars($championship)->implode('full_name', ', ') }}</h4>

        @include('assetto-corsa.results.championship-summary')

    @endforeach

@endsection
