@extends('page')

@section('header')
    <div class="page-header">
        <h1>Results</h1>
    </div>
@endsection

@section('content')

    @if (Auth::user() && Auth::user()->admin)
        <p>
            <a class="btn btn-small btn-info" href="{{ route('championship.create') }}">Add a new championship</a>
        </p>
    @endif

    <ul>
        @foreach($championships as $championship)
            <li>
                <a href="{{ route('championship.show', [$championship->id]) }}">
                    {{ $championship->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection