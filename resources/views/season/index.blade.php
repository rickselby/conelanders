@extends('page')

@section('header')
    <div class="page-header">
        <h1>Results</h1>
    </div>
@endsection

@section('content')

    @if (Auth::user() && Auth::user()->admin)
        <p>
            <a class="btn btn-small btn-info" href="{{ route('season.create') }}">Add a new season</a>
        </p>
    @endif

    <ul>
        @foreach($seasons as $season)
            <li>
                <a href="{{ route('season.show', [$season->id]) }}">
                    {{ $season->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection