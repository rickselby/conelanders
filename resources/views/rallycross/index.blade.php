@extends('page')

@section('header')
    <div class="page-header">
        <h1>Rallycross</h1>
    </div>
@endsection

@section('content')
    @foreach($championships AS $championship)

        <h2>
            <a href="{{ route('rallycross.results.championship', $championship) }}">
                {{ $championship->name }}
            </a>
        </h2>

        @include('rallycross.results.championship-summary')

    @endforeach

@endsection
