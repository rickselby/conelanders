@extends('page')

@section('header')
    <div class="page-header">
        <h1>Assetto Corsa</h1>
    </div>
@endsection

@section('content')
    @if ($currentChampionship)
        <h2>Current Championship: {{ $currentChampionship->name }}</h2>
        <div class="btn-group btn-group-lg" role="group">
            <a class="btn btn-primary" role="button"
               href="{{ route('assetto-corsa.standings.championship', $currentChampionship) }}">Driver Standings</a>
        </div>
    @endif

    @if (count($completeChampionships))
        <h2>Previous Championships</h2>
        @foreach($completeChampionships AS $championship)
            <h3>{{ $championship->name }}</h3>
            <div class="btn-group" role="group">
                <a class="btn btn-primary"  role="button"
                   href="{{ route('assetto-corsa.standings.championship', $championship) }}">Driver Standings</a>
            </div>
        @endforeach
    @endif

@endsection
