@extends('page')

@section('content')

    <ul>
        @foreach($championships AS $championship)
            <li>
                <a href="{{ route('dirt-rally.nationstandings.championship', $championship) }}">
                    {{ $championship->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection