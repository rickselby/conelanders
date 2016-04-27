@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li class="active">Results</li>
    </ol>
@endsection

@section('content')

    @if (Auth::user() && Auth::user()->admin)
        <p>
            <a class="btn btn-small btn-info" href="{{ route('dirt-rally.championship.create') }}">Add a new championship</a>
        </p>
    @endif

    <h2>Championships</h2>

    <ul>
        @foreach($championships as $championship)
            <li>
                <a href="{{ route('dirt-rally.championship.show', $championship) }}">
                    {{ $championship->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection