@extends('page')

@section('content')

    <ul>
        @foreach($systems AS $system)
            <li>
                <a href="{{ route('assetto-corsa.standings.system', $system) }}" class="tablesorter-noSort">
                    {{ $system->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection