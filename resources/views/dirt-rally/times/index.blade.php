@extends('page')

@section('content')

    <ul>
        @foreach($championships AS $championship)
            <li>
                <a href="{{ route('dirt-rally.times.championship', $championship) }}" class="tablesorter-noSort">
                    {{ $championship->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection