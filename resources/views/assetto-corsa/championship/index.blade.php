@extends('page')

@section('content')

    <h2>Championships</h2>

    <p>
        <a class="btn btn-small btn-info" href="{{ route('assetto-corsa.championship.create') }}">Add a new championship</a>
    </p>

    <ul>
        @foreach($championships as $championship)
            <li>
                <a href="{{ route('assetto-corsa.championship.show', $championship) }}">
                    {{ $championship->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection