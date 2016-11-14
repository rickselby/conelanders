@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $category->name }}</h1>
    </div>
@endsection

@section('content')
    @foreach($championships AS $championship)

        <h2>
            <a href="{{ route('races.results.championship', [$category, $championship]) }}">
                {{ $championship->name }}
            </a>
        </h2>
        <h4>{{ \RacesChampionships::cars($championship)->implode('name', ', ') }}</h4>

        @include('races.results.championship-summary')

    @endforeach

@endsection
