@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $system->name }} Standings</h1>
    </div>
@endsection

@section('content')

    <h2>Seasons</h2>
    <ul>
        @foreach($seasons as $season)
            <li>
                <a href="{{ route('standings.season', [$system->id, $season->id]) }}">
                    {{ $season->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection