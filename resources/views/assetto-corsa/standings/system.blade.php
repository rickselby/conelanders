@extends('page')

@section('content')

    <ul>
        @foreach($championships AS $championship)
            <li>
                <a href="{{ route('assetto-corsa.standings.championship', [$system, $championship]) }}">
                    {{ $championship->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection