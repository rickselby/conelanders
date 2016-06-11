@extends('page')

@section('content')

    <p>
        <a class="btn btn-small btn-info" href="{{ route('dirt-rally.championship.create') }}">Add a new championship</a>
    </p>

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