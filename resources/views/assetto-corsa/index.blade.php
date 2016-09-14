@extends('page')

@section('header')
    <div class="page-header">
        <h1>Assetto Corsa</h1>
    </div>
@endsection

@section('content')
    @foreach($championships AS $championship)

        <h2>
            <a href="{{ route('assetto-corsa.results.championship', $championship) }}">
                {{ $championship->name }}
            </a>
        </h2>
        <h4>{{ \ACChampionships::cars($championship)->implode('name', ', ') }}</h4>

        @include('assetto-corsa.results.championship-summary')

    @endforeach

@endsection
