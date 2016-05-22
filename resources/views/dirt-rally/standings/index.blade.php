@extends('page')

@section('content')

    <ul>
        @foreach($championships AS $championship)
            <li>
                <a href="{{ route('dirt-rally.standings.championship', $championship) }}">
                    {{ $championship->name }}
                </a>
            </li>
        @endforeach

    </ul>

@endsection